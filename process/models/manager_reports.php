<?php
use Dompdf\Dompdf;
use Dompdf\Options;

require_once __DIR__ . '/hewan.php';
require_once __DIR__ . '/kesehatan.php';
require_once __DIR__ . '/reproduksi.php';
require_once __DIR__ . '/transaksi.php';
require_once __DIR__ . '/../../vendor/autoload.php';

final class ManagerReportFilters
{
    public ?int $month;
    public ?int $year;
    public string $category;

    public function __construct(array $input = [])
    {
        $this->month = self::normalizeMonth($input['month'] ?? null);
        $this->year = self::normalizeYear($input['year'] ?? null);
        $this->category = trim((string) ($input['category'] ?? ''));
    }

    private static function normalizeMonth(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_numeric($value)) {
            $month = (int) $value;
            return ($month >= 1 && $month <= 12) ? $month : null;
        }

        $map = [
            'januari' => 1,
            'februari' => 2,
            'maret' => 3,
            'april' => 4,
            'mei' => 5,
            'juni' => 6,
            'juli' => 7,
            'agustus' => 8,
            'september' => 9,
            'oktober' => 10,
            'november' => 11,
            'desember' => 12,
        ];

        $key = strtolower(trim((string) $value));
        return $map[$key] ?? null;
    }

    private static function normalizeYear(mixed $value): ?int
    {
        if ($value === null || $value === '' || !is_numeric($value)) {
            return null;
        }

        $year = (int) $value;
        return $year > 0 ? $year : null;
    }
}

abstract class AbstractManagerReport
{
    protected mysqli $db;
    protected ManagerReportFilters $filters;
    private ?array $baseRows = null;
    private ?array $filteredRows = null;

    public function __construct(mysqli $db, ?ManagerReportFilters $filters = null)
    {
        $this->db = $db;
        $this->filters = $filters ?? new ManagerReportFilters();
    }

    abstract public function getSlug(): string;

    abstract public function getTitle(): string;

    abstract public function getCategoryLabel(): string;

    abstract public function getAllCategoryLabel(): string;

    abstract public function getColumns(): array;

    abstract public function getSummaryCards(): array;

    abstract public function getChartData(): array;

    abstract protected function fetchBaseRows(): array;

    abstract protected function getDateValue(array $row): ?string;

    abstract protected function getCategoryValuesForRow(array $row): array;

    public function getRows(): array
    {
        if ($this->filteredRows === null) {
            $this->filteredRows = $this->applyFilters($this->getBaseRows());
        }

        return $this->filteredRows;
    }

    public function getBaseRows(): array
    {
        if ($this->baseRows === null) {
            $this->baseRows = $this->fetchBaseRows();
        }

        return $this->baseRows;
    }

    public function getMonthOptions(): array
    {
        $options = [['value' => '', 'label' => 'Semua Bulan']];
        for ($month = 1; $month <= 12; $month++) {
            $options[] = ['value' => (string) $month, 'label' => self::monthName($month)];
        }

        return $options;
    }

    public function getYearOptions(): array
    {
        $years = [];

        foreach ($this->getBaseRows() as $row) {
            $date = $this->getDateValue($row);
            if (!$date || !strtotime($date)) {
                continue;
            }

            $years[(int) date('Y', strtotime($date))] = true;
        }

        $currentYear = (int) date('Y');
        if ($years === []) {
            $years[$currentYear] = true;
        }

        $minYear = min(array_keys($years));
        $maxYear = max($currentYear, max(array_keys($years)));

        $options = [['value' => '', 'label' => 'Semua Tahun']];
        for ($year = $maxYear; $year >= $minYear; $year--) {
            $options[] = ['value' => (string) $year, 'label' => (string) $year];
        }

        return $options;
    }

    public function getCategoryOptions(): array
    {
        $categories = [];

        foreach ($this->getBaseRows() as $row) {
            foreach ($this->getCategoryValuesForRow($row) as $value) {
                $trimmed = trim((string) $value);
                if ($trimmed !== '') {
                    $categories[$trimmed] = true;
                }
            }
        }

        ksort($categories);

        $options = [['value' => '', 'label' => $this->getAllCategoryLabel()]];
        foreach (array_keys($categories) as $category) {
            $options[] = ['value' => $category, 'label' => $category];
        }

        return $options;
    }

    public function getSelectedMonth(): string
    {
        return $this->filters->month === null ? '' : (string) $this->filters->month;
    }

    public function getSelectedYear(): string
    {
        return $this->filters->year === null ? '' : (string) $this->filters->year;
    }

    public function getSelectedCategory(): string
    {
        return $this->filters->category;
    }

    public function getSelectedFilterLabels(): array
    {
        return [
            'month' => $this->filters->month === null ? 'Semua Bulan' : self::monthName($this->filters->month),
            'year' => $this->filters->year === null ? 'Semua Tahun' : (string) $this->filters->year,
            'category' => $this->filters->category !== '' ? $this->filters->category : $this->getAllCategoryLabel(),
        ];
    }

    public function getExportFilename(string $extension): string
    {
        $stamp = date('Ymd_His');
        return sprintf('%s_%s.%s', $this->getSlug(), $stamp, $extension);
    }

    protected function applyFilters(array $rows): array
    {
        return array_values(array_filter($rows, function (array $row): bool {
            $date = $this->getDateValue($row);
            $timestamp = $date && strtotime($date) ? strtotime($date) : null;

            if ($this->filters->month !== null) {
                if ($timestamp === null || (int) date('n', $timestamp) !== $this->filters->month) {
                    return false;
                }
            }

            if ($this->filters->year !== null) {
                if ($timestamp === null || (int) date('Y', $timestamp) !== $this->filters->year) {
                    return false;
                }
            }

            if ($this->filters->category !== '') {
                return in_array($this->filters->category, $this->getCategoryValuesForRow($row), true);
            }

            return true;
        }));
    }

    protected function buildLastSixMonthTrend(array $rows, callable $valueResolver): array
    {
        return $this->buildLastNMonthTrend($rows, $valueResolver, 6);
    }

    protected function buildYearJanToDecTrend(int $year, array $rows, callable $valueResolver): array
    {
        $groups = [];

        foreach ($rows as $row) {
            $date = $this->getDateValue($row);
            if (!$date || !strtotime($date)) {
                continue;
            }

            $timestamp = strtotime($date);
            $rowYear = (int) date('Y', $timestamp);
            if ($rowYear !== $year) {
                continue;
            }

            $month = (int) date('n', $timestamp);
            if ($month < 1 || $month > 12) {
                continue;
            }

            $key = sprintf('%04d-%02d', $year, $month);

            $resolved = $valueResolver($row);
            $normalized = is_numeric($resolved) ? $resolved : 0;

            $groups[$key] = ($groups[$key] ?? 0) + ($normalized);
        }

        $labels = [];
        $values = [];

        for ($m = 1; $m <= 12; $m++) {
            $key = sprintf('%04d-%02d', $year, $m);
            $value = $groups[$key] ?? 0;
            $labels[] = self::monthName($m);

            $values[] = (is_float($value) && abs($value - round($value)) < 1e-9)
                ? (int) round($value)
                : $value;
        }

        return ['labels' => $labels, 'values' => $values];
    }


    protected function buildLastNMonthTrend(array $rows, callable $valueResolver, int $countMonths): array
    {
        $groups = [];


        foreach ($rows as $row) {
            $date = $this->getDateValue($row);
            if (!$date || !strtotime($date)) {
                continue;
            }

            $key = date('Y-m', strtotime($date));

            // Kunci masalah decimal & label tidak jelas biasanya berasal dari hasil resolver.
            // Untuk chart berbasis count, resolver wajib menghasilkan integer.
            $resolved = $valueResolver($row);

            // Normalisasi agar COUNT selalu integer dan trend chart tidak menghasilkan float aneh.
            // SUM nominal/rupiah juga dibikin aman (tetap float jika memang uang).
            $normalized = is_numeric($resolved) ? $resolved : 0;

            $groups[$key] = ($groups[$key] ?? 0) + ($normalized);
        }

        ksort($groups);
        $groups = array_slice($groups, -$countMonths, null, true);

        $labels = [];
        $values = [];

        foreach ($groups as $key => $value) {
            $year = substr($key, 0, 4);
            $month = (int) substr($key, 5, 2);

            $labels[] = self::monthName($month) . ' ' . $year;

            // Pastikan axis count tidak akan menampilkan desimal.
            $values[] = (is_float($value) && abs($value - round($value)) < 1e-9)
                ? (int) round($value)
                : $value;
        }

        return ['labels' => $labels, 'values' => $values];
    }

    protected static function monthName(int $month): string
    {
        return [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ][$month] ?? 'Tidak Diketahui';
    }

    protected static function labelJenisHewan(string $jenis): string
    {
        return match ($jenis) {
            'sapi_perah' => 'Sapi Perah',
            'sapi_po' => 'Sapi PO',
            default => ucwords(str_replace('_', ' ', $jenis)),
        };
    }

    protected static function labelStatusHewan(string $status): string
    {
        return match ($status) {
            'produktif' => 'Produktif',
            'tdk_produktif' => 'Tidak Produktif',
            default => ucwords(str_replace('_', ' ', $status)),
        };
    }

    protected static function labelStatusKesehatan(string $status): string
    {
        return match ($status) {
            'sehat' => 'Sehat',
            'observasi', 'dalam_observasi' => 'Observasi',
            'perawatan', 'dalam_perawatan' => 'Dalam Perawatan',
            default => ucwords(str_replace('_', ' ', $status)),
        };
    }

    protected static function labelStatusIb(string $status): string
    {
        return match ($status) {
            'berhasil' => 'Berhasil',
            'tdk_berhasil' => 'Tidak Berhasil',
            'proses' => 'Proses IB',
            default => 'Belum Ada',
        };
    }

    protected static function labelJenisProduk(string $jenis): string
    {
        return match ($jenis) {
            'hewan' => 'Hewan',
            'susu' => 'Susu',
            'rumput' => 'Rumput',
            default => ucwords(str_replace('_', ' ', $jenis)),
        };
    }

    protected static function labelStatusTransaksi(string $status): string
    {
        return match ($status) {
            'menunggu_verifikasi' => 'Menunggu',
            'telah_dikonfirmasi' => 'Selesai',
            'dibatalkan' => 'Ditolak',
            default => ucwords(str_replace('_', ' ', $status)),
        };
    }

    protected static function labelMetodePembayaran(string $metode): string
    {
        return match ($metode) {
            'cod' => 'COD',
            'transfer' => 'Transfer',
            default => strtoupper($metode),
        };
    }

    protected static function formatDate(string $date, string $format = 'd-m-Y'): string
    {
        if ($date === '' || !strtotime($date)) {
            return '-';
        }

        $formatted = date($format, strtotime($date));
        $months = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember',
            'Jan' => 'Jan',
            'Feb' => 'Feb',
            'Mar' => 'Mar',
            'Apr' => 'Apr',
            'Jun' => 'Jun',
            'Jul' => 'Jul',
            'Aug' => 'Agu',
            'Sep' => 'Sep',
            'Oct' => 'Okt',
            'Nov' => 'Nov',
            'Dec' => 'Des',
        ];

        return strtr($formatted, $months);
    }

    protected static function formatCurrency(float $amount): string
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }

    protected static function calculateAgeLabel(string $birthDate): string
    {
        if ($birthDate === '' || !strtotime($birthDate)) {
            return '-';
        }

        $birth = new DateTime($birthDate);
        $today = new DateTime('today');
        $diff = $today->diff($birth);

        return $diff->y . ' th ' . $diff->m . ' bln';
    }

    protected static function resolveAnimalImage(string $path): string
    {
        $trimmed = trim($path);
        if ($trimmed === '') {
            return '../../public/images/bgheader_produk.png';
        }

        if (str_starts_with($trimmed, 'http://') || str_starts_with($trimmed, 'https://')) {
            return $trimmed;
        }

        $normalized = preg_replace('#^(\.\./|./)+#', '', str_replace('\\', '/', $trimmed)) ?? $trimmed;
        $normalized = ltrim($normalized, '/');

        if (str_starts_with($normalized, 'public/') || str_starts_with($normalized, 'uploads/')) {
            return '../../' . $normalized;
        }

        return '../../uploads/hewan/' . basename($normalized);
    }

    public function getPdfOrientation(): string
    {
        return 'landscape';
    }
}

final class PopulationReport extends AbstractManagerReport
{
    public function getReproductionChartData(): array
    {
        $reproductionModel = new Reproduksi($this->db);
        $reproductionRows = $reproductionModel->getAll();

        $statusCounts = [];

        foreach ($reproductionRows as $row) {
            $status = trim((string) ($row['status_ib'] ?? ''));
            if ($status === '') {
                continue;
            }

            $statusCounts[$status] = ($statusCounts[$status] ?? 0) + 1;
        }

        return [
            'labels' => array_keys($statusCounts),
            'values' => array_values($statusCounts),
        ];
    }

    public function getSlug(): string

    {
        return 'laporan_populasi';
    }

    public function getTitle(): string
    {
        return 'Laporan Populasi Hewan';
    }

    public function getCategoryLabel(): string
    {
        return 'Jenis Hewan';
    }

    public function getAllCategoryLabel(): string
    {
        return 'Semua Hewan';
    }

    public function getColumns(): array
    {
        return [
            'kode' => 'Kode Hewan',
            'jenis' => 'Jenis Hewan',
            'status_populasi' => 'Status Populasi',
            'status_reproduksi' => 'Status Reproduksi',
            'status_kesehatan' => 'Status Kesehatan',
            'kandang' => 'No Kandang',
            'umur' => 'Umur',
            'pemeriksaan_terakhir' => 'Pemeriksaan Terakhir',
        ];
    }

    public function getSummaryCards(): array
    {
        $rows = $this->getRows();
        $counts = [];
        foreach ($rows as $row) {
            $counts[$row['jenis']] = ($counts[$row['jenis']] ?? 0) + 1;
        }

        return [
            ['label' => 'Sapi Perah', 'value' => $counts['Sapi Perah'] ?? 0, 'icon' => '../../public/svg/sapi.svg'],
            ['label' => 'Sapi PO', 'value' => $counts['Sapi PO'] ?? 0, 'icon' => '../../public/svg/sapi.svg'],
            ['label' => 'Total Hewan', 'value' => count($rows), 'icon' => '../../public/svg/kandang.svg'],
        ];
    }

    public function getChartData(): array
    {
        $rows = $this->getRows();
        $statusCounts = [];
        $reproductionCounts = [];

        foreach ($rows as $row) {
            $statusCounts[$row['status_populasi']] = ($statusCounts[$row['status_populasi']] ?? 0) + 1;

            // Status reproduksi hanya dihitung jika benar-benar ada data reproduksi
            $reproStatus = $row['status_reproduksi'] ?? null;
            if ($reproStatus === null || $reproStatus === '') {
                continue;
            }

            $reproductionCounts[$reproStatus] = ($reproductionCounts[$reproStatus] ?? 0) + 1;
        }


        return [
            'status' => ['labels' => array_keys($statusCounts), 'values' => array_values($statusCounts)],
            // paksa nilai chart reproduksi integer (menghindari decimal/double)
            'reproduction' => [
                'labels' => array_keys($reproductionCounts),
                'values' => array_map(static fn($v): int => (int) $v, array_values($reproductionCounts)),
            ],
            // Trend penjualan = uang masuk (total_tagihan) dari transaksi yang sudah terverifikasi (telah_dikonfirmasi)
            'trend' => $this->buildLastSixMonthTrend(
                $rows,
                static fn(array $row): float => ((string) ($row['status'] ?? $row['status_transaksi'] ?? '')) === 'Selesai'
                    ? (float) ($row['total_harga_raw'] ?? 0)
                    : 0
            ),


        ];
    }

    protected function fetchBaseRows(): array
    {
        $hewanModel = new Hewan($this->db);
        $healthModel = new Kesehatan($this->db);
        $reproductionModel = new Reproduksi($this->db);

        $animals = $hewanModel->getAll();
        $healthRows = $healthModel->getAll();
        $reproductionRows = $reproductionModel->getAll();

        $latestHealth = [];
        $healthCounts = [];

        foreach ($healthRows as $row) {
            $animalId = (int) ($row['id_hewan'] ?? 0);
            if ($animalId === 0) {
                continue;
            }

            $healthCounts[$animalId] = ($healthCounts[$animalId] ?? 0) + 1;
            $currentDate = $row['tgl_pemeriksaan'] ?? '';

            if (!isset($latestHealth[$animalId]) || strcmp((string) $latestHealth[$animalId]['tgl_pemeriksaan'], (string) $currentDate) < 0) {
                $latestHealth[$animalId] = $row;
            }
        }

        $latestReproduction = [];
        $reproductionCounts = [];

        foreach ($reproductionRows as $row) {
            $animalId = (int) ($row['id_hewan'] ?? 0);
            if ($animalId === 0) {
                continue;
            }

            $reproductionCounts[$animalId] = ($reproductionCounts[$animalId] ?? 0) + 1;
            $currentDate = $row['tgl_ib'] ?? '';

            if (!isset($latestReproduction[$animalId]) || strcmp((string) $latestReproduction[$animalId]['tgl_ib'], (string) $currentDate) < 0) {
                $latestReproduction[$animalId] = $row;
            }
        }

        $rows = [];

        foreach ($animals as $animal) {
            $animalId = (int) ($animal['id_hewan'] ?? 0);
            $health = $latestHealth[$animalId] ?? [];
            $reproduction = $latestReproduction[$animalId] ?? [];
            $code = (string) ($animal['kode_hewan'] ?? ('HF-' . $animalId));

            $rows[] = [
                'id' => $animalId,
                'kode' => $code,
                'nama' => 'Ternak ' . $code,
                'jenis' => self::labelJenisHewan((string) ($animal['jenis_hewan'] ?? '')),
                'berat' => (float) ($animal['berat_badan'] ?? 0),
                'jenis_kelamin' => ucfirst((string) ($animal['jenis_kelamin'] ?? '-')),
                'kandang' => (string) ($animal['no_kandang'] ?? '-'),
                'tanggal_lahir' => (string) ($animal['tgl_lahir'] ?? ''),
                'umur' => self::calculateAgeLabel((string) ($animal['tgl_lahir'] ?? '')),
                'status_populasi' => self::labelStatusHewan((string) ($animal['status_hewan'] ?? '')),
                'status_kesehatan' => self::labelStatusKesehatan((string) ($health['status_kesehatan'] ?? '')),
                // Abaikan hewan yang tidak memiliki data reproduksi (harusnya tidak ikut dihitung chart)
                'status_reproduksi' => empty($reproduction)
                    ? null
                    : self::labelStatusIb((string) ($reproduction['status_ib'] ?? '')),

                'pemeriksaan_terakhir' => (string) ($health['tgl_pemeriksaan'] ?? ''),
                'total_pemeriksaan' => $healthCounts[$animalId] ?? 0,
                'total_reproduksi' => $reproductionCounts[$animalId] ?? 0,
                'catatan_medis' => trim((string) ($health['catatan'] ?? '')) !== '' ? (string) $health['catatan'] : 'Belum ada catatan medis.',
                'gambar' => self::resolveAnimalImage((string) ($animal['foto_hewan'] ?? '')),
                'diagnosis_terakhir' => (string) ($health['diagnosis'] ?? '-'),
                'tindakan_terakhir' => (string) ($health['tindakan'] ?? '-'),
            ];
        }

        return $rows;
    }

    protected function getDateValue(array $row): ?string
    {
        return $row['tgl_lahir'] ?? null;
    }

    protected function getCategoryValuesForRow(array $row): array
    {
        return [$row['jenis'] ?? ''];
    }
}

final class HealthReport extends AbstractManagerReport
{
    public function getSlug(): string
    {
        return 'laporan_kesehatan';
    }

    public function getTitle(): string
    {
        return 'Laporan Kesehatan Hewan';
    }

    public function getCategoryLabel(): string
    {
        return 'Jenis Hewan';
    }

    public function getAllCategoryLabel(): string
    {
        return 'Semua Hewan';
    }

    public function getColumns(): array
    {
        return [
            'kode_hewan' => 'Kode Hewan',
            'jenis_hewan' => 'Jenis Hewan',
            'tanggal' => 'Tanggal',
            'status' => 'Status',
            'diagnosis' => 'Diagnosis',
            'tindakan' => 'Tindakan',
            'catatan' => 'Catatan',
        ];
    }

    public function getSummaryCards(): array
    {
        $rows = $this->getRows();
        $statusCounts = [];

        foreach ($rows as $row) {
            $statusCounts[$row['status']] = ($statusCounts[$row['status']] ?? 0) + 1;
        }

        return [
            ['label' => 'Sehat', 'value' => $statusCounts['Sehat'] ?? 0, 'icon' => '../../public/svg/heart.svg'],
            ['label' => 'Observasi', 'value' => $statusCounts['Observasi'] ?? 0, 'icon' => '../../public/svg/lab.svg'],
            ['label' => 'Dalam Perawatan', 'value' => $statusCounts['Dalam Perawatan'] ?? 0, 'icon' => '../../public/svg/warning.svg'],
            ['label' => 'Total Pemeriksaan', 'value' => count($rows), 'icon' => '../../public/svg/suntik.svg'],
        ];
    }

    public function getChartData(): array
    {
        // IMPORTANT:
        // - Chart harus berbasis data kesehatan yang benar-benar ada (kesehatan table).
        // - Tidak boleh pakai dummy/fallback.
        // - Karena getRows() sudah hasil applyFilters() dari baseRows kesehatan,
        //   maka $rows di sini benar-benar merepresentasikan data yang valid.

        $rows = $this->getRows();

        $casesByAnimal = [];
        foreach ($rows as $row) {
            $jenis = (string) ($row['jenis_hewan'] ?? '');
            if ($jenis === '') {
                continue;
            }
            $casesByAnimal[$jenis] = ($casesByAnimal[$jenis] ?? 0) + 1;
        }

        // Jika tidak ada data pemeriksaan untuk filter aktif,
        // pastikan chart tidak “naik” (nilai & axis tetap 0).
        if ($rows === []) {
            return [
                'casesByAnimal' => ['labels' => [], 'values' => []],
                'trend' => ['labels' => [], 'values' => []],
            ];
        }

        return [
            'casesByAnimal' => [
                'labels' => array_keys($casesByAnimal),
                'values' => array_map(static fn($v): int => (int) $v, array_values($casesByAnimal)),
            ],
            // COUNT pemeriksaan per bulan (integer)
            'trend' => $this->buildLastSixMonthTrend($rows, static fn(array $row): int => 1),
        ];
    }

    protected function fetchBaseRows(): array
    {
        $healthModel = new Kesehatan($this->db);
        $healthRows = $healthModel->getAll();

        return array_map(function (array $row): array {
            return [
                'id_kesehatan' => (int) ($row['id_kesehatan'] ?? 0),
                'id_hewan' => (int) ($row['id_hewan'] ?? 0),
                'kode_hewan' => (string) ($row['kode_hewan'] ?? '-'),
                'jenis_hewan' => self::labelJenisHewan((string) ($row['jenis_hewan'] ?? '')),
                'tanggal' => (string) ($row['tgl_pemeriksaan'] ?? ''),
                'status' => self::labelStatusKesehatan((string) ($row['status_kesehatan'] ?? '')),
                'diagnosis' => trim((string) ($row['diagnosis'] ?? '')) !== '' ? (string) $row['diagnosis'] : '-',
                'tindakan' => trim((string) ($row['tindakan'] ?? '')) !== '' ? (string) $row['tindakan'] : '-',
                'catatan' => trim((string) ($row['catatan'] ?? '')) !== '' ? (string) $row['catatan'] : '-',
                'petugas' => '-',
            ];
        }, $healthRows);
    }

    protected function getDateValue(array $row): ?string
    {
        return $row['tanggal'] ?? null;
    }

    protected function getCategoryValuesForRow(array $row): array
    {
        return [$row['jenis_hewan'] ?? ''];
    }
}

final class TransactionReport extends AbstractManagerReport
{
    public function getSlug(): string
    {
        return 'laporan_transaksi';
    }

    public function getTitle(): string
    {
        return 'Laporan Transaksi Penjualan';
    }

    public function getCategoryLabel(): string
    {
        return 'Jenis Produk';
    }

    public function getAllCategoryLabel(): string
    {
        return 'Semua Produk';
    }

    public function getColumns(): array
    {
        return [
            'id' => 'ID Transaksi',
            'tanggal' => 'Tanggal',
            'jenis_produk' => 'Jenis Produk',
            'produk' => 'Produk',
            'nama_pembeli' => 'Nama Pembeli',
            'total_harga' => 'Total Harga',
            'metode' => 'Metode',
            'status' => 'Status',
        ];
    }

    public function getSummaryCards(): array
    {
        $rows = $this->getRows();
        $completedSales = count(array_filter($rows, static fn(array $row): bool => ($row['status'] ?? '') === 'Selesai'));
        $totalRevenue = array_sum(array_map(static fn(array $row): float => (float) ($row['total_harga_raw'] ?? 0), $rows));

        return [
            ['label' => 'Total Transaksi', 'value' => count($rows), 'icon' => '../../public/svg/paket.svg'],
            ['label' => 'Penjualan Selesai', 'value' => $completedSales, 'icon' => '../../public/svg/uang.svg'],
            ['label' => 'Total Pendapatan', 'value' => self::formatCurrency($totalRevenue), 'icon' => '../../public/svg/profit.svg'],
        ];
    }

    public function getChartData(): array
    {
        $rows = $this->getRows();

        // Hitung penjualan per jenis produk (REAL dari detail_transaksi), tanpa dummy.
        // Tapi agar sumbu X tidak pakai angka 0/1/2, kita kembalikan label readable:
        // hewan | susu | rumput (sesuai enum data_produk.jenis_produk)
        $productCountsByJenis = [
            'hewan' => 0,
            'susu' => 0,
            'rumput' => 0,
        ];

        foreach ($rows as $row) {
            foreach (($row['jenis_produk_list'] ?? []) as $jenis) {
                $jenis = trim((string) $jenis);
                if ($jenis === '') {
                    continue;
                }
                if (!array_key_exists($jenis, $productCountsByJenis)) {
                    // fallback untuk nilai tak terduga, tetap pakai as-is
                    $productCountsByJenis[$jenis] = ($productCountsByJenis[$jenis] ?? 0) + 1;
                    continue;
                }
                $productCountsByJenis[$jenis] = ($productCountsByJenis[$jenis] ?? 0) + 1;
            }
        }

        // Urutan label tetap user-friendly (bukan urutan angka insertion).
        $jenisUrutan = ['hewan', 'rumput', 'susu'];
        $labels = [];
        $values = [];

        foreach ($jenisUrutan as $jenis) {
            if (!array_key_exists($jenis, $productCountsByJenis)) {
                continue;
            }
            $labels[] = self::labelJenisProduk((string) $jenis);
            $values[] = (int) ($productCountsByJenis[$jenis] ?? 0);
        }

        // Tambahkan jenis lain yang mungkin ada (jika ada data di luar enum).
        foreach ($productCountsByJenis as $jenis => $count) {
            if (in_array($jenis, $jenisUrutan, true)) {
                continue;
            }
            $labels[] = self::labelJenisProduk((string) $jenis);
            $values[] = (int) ($count ?? 0);
        }

        $selectedYear = $this->filters->year;
        $selectedYear = $selectedYear ?? (int) date('Y');

        return [
            'products' => [
                'labels' => $labels,
                'values' => $values,
            ],
            // Trend penjualan (uang masuk) dari transaksi yang sudah terverifikasi (telah_dikonfirmasi)
            // Tampilan: Jan-Des untuk tahun kalender yang dipilih.
            'trend' => $this->buildYearJanToDecTrend(
                $selectedYear,
                $rows,
                static fn(array $row): float => ((string) ($row['status'] ?? $row['status_transaksi'] ?? '')) === 'Selesai'
                    ? (float) ($row['total_harga_raw'] ?? 0)
                    : 0
            ),
        ];
    }



    protected function fetchBaseRows(): array
    {
        $transactionModel = new Transaksi($this->db);
        $transactions = $transactionModel->getAllForReport();

        return array_map(function (array $row): array {
            $jenisList = array_values(array_filter(array_map(
                static fn(string $jenis): string => self::labelJenisProduk(trim($jenis)),
                explode(',', (string) ($row['jenis_produk'] ?? ''))
            )));

            return [
                'id' => '#ORD-' . str_pad((string) ($row['id_transaksi'] ?? 0), 3, '0', STR_PAD_LEFT),
                'tanggal' => (string) ($row['tgl_transaksi'] ?? ''),
                'jenis_produk' => $jenisList !== [] ? implode(', ', $jenisList) : 'Produk Tidak Diketahui',
                'jenis_produk_list' => $jenisList,
                'produk' => trim((string) ($row['produk'] ?? '')) !== '' ? (string) $row['produk'] : '-',
                'nama_pembeli' => (string) ($row['nama_pembeli'] ?? '-'),
                'total_harga_raw' => (float) ($row['total_tagihan'] ?? 0),
                'total_harga' => self::formatCurrency((float) ($row['total_tagihan'] ?? 0)),
                'metode' => self::labelMetodePembayaran((string) ($row['metode_pembayaran'] ?? '')),
                'status' => self::labelStatusTransaksi((string) ($row['status_transaksi'] ?? '')),
            ];
        }, $transactions);
    }

    protected function getDateValue(array $row): ?string
    {
        return $row['tanggal'] ?? null;
    }

    protected function getCategoryValuesForRow(array $row): array
    {
        return $row['jenis_produk_list'] ?? [];
    }
}

final class DetailAnimalReport extends AbstractManagerReport
{
    private int $animalId;
    private ?array $animal = null;
    private ?array $healthHistory = null;
    private ?array $reproductionHistory = null;

    public function __construct(mysqli $db, ?ManagerReportFilters $filters = null, int $animalId = 0)
    {
        parent::__construct($db, $filters);
        $this->animalId = $animalId;
    }

    public function getSlug(): string
    {
        return 'detail_hewan_ternak';
    }

    public function getTitle(): string
    {
        return 'Detail Hewan Ternak';
    }

    public function getCategoryLabel(): string
    {
        return 'Hewan';
    }

    public function getAllCategoryLabel(): string
    {
        return 'Semua Hewan';
    }

    public function getColumns(): array
    {
        return [
            'tanggal' => 'Tanggal',
            'diagnosis' => 'Diagnosis',
            'tindakan' => 'Tindakan',
        ];
    }

    public function getSummaryCards(): array
    {
        $animal = $this->getAnimal();

        return [
            ['label' => 'Pemeriksaan Terakhir', 'value' => $animal['pemeriksaan_terakhir_label'] ?? '-'],
            ['label' => 'Total Pemeriksaan', 'value' => (string) ($animal['total_pemeriksaan'] ?? 0)],
            ['label' => 'Total Reproduksi', 'value' => (string) ($animal['total_reproduksi'] ?? 0)],
        ];
    }

    public function getChartData(): array
    {
        return [];
    }

    public function getAnimal(): array
    {
        if ($this->animal !== null) {
            return $this->animal;
        }

        $populationReport = new PopulationReport($this->db, $this->filters);
        $animals = $populationReport->getBaseRows();

        $this->animal = [
            'id' => 0,
            'kode' => '-',
            'nama' => 'Data tidak tersedia',
            'jenis' => '-',
            'umur' => '-',
            'kandang' => '-',
            'status_kesehatan' => '-',
            'pemeriksaan_terakhir' => '',
            'pemeriksaan_terakhir_label' => '-',
            'total_pemeriksaan' => 0,
            'total_reproduksi' => 0,
            'catatan_medis' => 'Belum ada data ternak.',
            'gambar' => '../../public/images/bgheader_produk.png',
        ];

        foreach ($animals as $candidate) {
            if ((int) ($candidate['id'] ?? 0) !== $this->animalId) {
                continue;
            }

            $candidate['pemeriksaan_terakhir_label'] = self::formatDate((string) ($candidate['pemeriksaan_terakhir'] ?? ''), 'd M Y');
            $this->animal = $candidate;
            break;
        }

        return $this->animal;
    }

    public function getHealthHistory(): array
    {
        if ($this->healthHistory !== null) {
            return $this->healthHistory;
        }

        $healthModel = new Kesehatan($this->db);
        $healthRows = $healthModel->getByHewanId($this->animalId);

        $this->healthHistory = array_map(function (array $row): array {
            return [
                'tanggal' => (string) ($row['tgl_pemeriksaan'] ?? ''),
                'tanggal_label' => self::formatDate((string) ($row['tgl_pemeriksaan'] ?? ''), 'd M Y'),
                'diagnosis' => trim((string) ($row['diagnosis'] ?? '')) !== '' ? (string) $row['diagnosis'] : '-',
                'tindakan' => trim((string) ($row['tindakan'] ?? '')) !== '' ? (string) $row['tindakan'] : '-',
            ];
        }, $healthRows);

        return $this->healthHistory;
    }

    public function getReproductionHistory(): array
    {
        if ($this->reproductionHistory !== null) {
            return $this->reproductionHistory;
        }

        $reproductionModel = new Reproduksi($this->db);
        $rows = $reproductionModel->getByHewanId($this->animalId);

        $this->reproductionHistory = array_map(function (array $row): array {
            return [
                'tanggal_ib' => (string) ($row['tgl_ib'] ?? ''),
                'tanggal_ib_label' => self::formatDate((string) ($row['tgl_ib'] ?? ''), 'd M Y'),
                'tgl_perkiraan' => (string) ($row['tgl_perkiraan'] ?? ''),
                'tgl_perkiraan_label' => self::formatDate((string) ($row['tgl_perkiraan'] ?? ''), 'd M Y'),
                'petugas' => '-',
                'hasil' => self::labelStatusIb((string) ($row['status_ib'] ?? '')),
                'keterangan' => !empty($row['ib_ke']) ? 'IB ke-' . $row['ib_ke'] : '-',
            ];
        }, $rows);

        return $this->reproductionHistory;
    }

    public function getPdfOrientation(): string
    {
        return 'portrait';
    }

    protected function fetchBaseRows(): array
    {
        return $this->getHealthHistory();
    }

    protected function getDateValue(array $row): ?string
    {
        return $row['tanggal'] ?? null;
    }

    protected function getCategoryValuesForRow(array $row): array
    {
        return [];
    }
}

abstract class AbstractReportExporter
{
    abstract public function download(AbstractManagerReport $report): void;

    protected function buildFilterSummary(AbstractManagerReport $report): string
    {
        $filters = $report->getSelectedFilterLabels();

        return sprintf(
            'Filter: %s | %s | %s',
            $filters['month'],
            $filters['year'],
            $filters['category']
        );
    }

    protected function normalizeText(string $text): string
    {
        $normalized = preg_replace('/\s+/', ' ', trim($text)) ?? '';
        return $normalized === '' ? '-' : $normalized;
    }
}


final class PdfReportExporter extends AbstractReportExporter
{
    
    public function download(AbstractManagerReport $report): void
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);

        $title = $report->getTitle();
        $rows = $report->getRows();
        $columns = $report->getColumns();

        $html = $this->buildHtml($report, $title, $rows, $columns);

        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', $report->getPdfOrientation());
        $dompdf->render();

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $report->getExportFilename('pdf') . '"');

        echo $dompdf->output();
        exit;
    }

    private function buildHtml(
        AbstractManagerReport $report,
        string $title,
        array $rows,
        array $columns
    ): string {

        $generatedAt = date('d-m-Y H:i:s');

        if ($report instanceof DetailAnimalReport) {
            return $this->buildDetailAnimalHtml($report, $generatedAt);
        }

        $month = $report->getSelectedMonth();
        $year = $report->getSelectedYear();
        $category = $report->getSelectedCategory();

        $monthNames = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        // =========================
        // HEADER FILTER
        // =========================

        $filterText = '';

        if ($month !== '') {
            $filterText .= $monthNames[(int)$month] ?? '';
        }

        if ($year !== '') {
            $filterText .= ($filterText ? ' ' : '') . $year;
        }

        if ($category !== '') {
            $filterText .= ($filterText ? ' | ' : '') . $category;
        }

        // kalau tidak ada filter sama sekali
        if ($filterText === '') {
            $filterText = date('F Y');
        }

        // =========================
        // JUDUL DETAIL
        // =========================

        $detailTitle = match ($report->getSlug()) {
            'laporan_populasi' => 'Detail Data Ternak',
            'laporan_kesehatan' => 'Detail Riwayat Kesehatan Ternak',
            'laporan_transaksi' => 'Detail Transaksi Penjualan',
            default => 'Detail Data',
        };

        // =========================
        // TABLE ROWS
        // =========================

        $tableRows = '';

        if (empty($rows)) {

            $tableRows .= '
                <tr>
                    <td colspan="' . count($columns) . '" class="empty">
                        Tidak ada data
                    </td>
                </tr>
            ';
        } else {

            foreach ($rows as $row) {

                $tableRows .= '<tr>';

                foreach (array_keys($columns) as $key) {

                    $value = $row[$key] ?? '-';

                    if (is_array($value)) {
                        $value = implode('<br>', $value);
                    }

                    // format tanggal khusus
                    if (
                        in_array($key, [
                            'tanggal',
                            'pemeriksaan_terakhir'
                        ])
                    ) {
                        if ($value && strtotime($value)) {
                            $value = date('d/m/Y', strtotime($value));
                        }
                    }

                    $tableRows .= '
                        <td>' . htmlspecialchars((string)$value) . '</td>
                    ';
                }

                $tableRows .= '</tr>';
            }
        }

        // =========================
        // TABLE HEADER
        // =========================

        $tableHeaders = '';

        foreach ($columns as $label) {
            $tableHeaders .= '
                <th>' . htmlspecialchars($label) . '</th>
            ';
        }

        // =========================
        // HTML
        // =========================

        $logoPath = realpath(__DIR__ . '/../../public/images/logo/logo2.png');

        $logoBase64 = '';

        if ($logoPath && file_exists($logoPath)) {
            $imageType = pathinfo($logoPath, PATHINFO_EXTENSION);
            $imageData = base64_encode(file_get_contents($logoPath));

            $logoBase64 = 'data:image/' . $imageType . ';base64,' . $imageData;
        }

        return '
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">

            <style>

                body{
                    font-family: sans-serif;
                    color: #111;
                    margin: 0;
                    padding: 0;
                }

                .header{
                    padding: 40px 55px 20px 55px;
                }

                .brand{
                    display: inline-block;
                    vertical-align: middle;
                    margin-right: 15px;
                }

                .brand img{
                    width: 170px;
                    height: auto;
                }

                .title{
                    display: inline-block;
                    vertical-align: middle;
                    font-size: 34px;
                    font-weight: 700;
                    text-transform: uppercase;
                }

                .sub-header{
                    background: #F1F2F5;
                    padding: 25px 55px;
                    margin-top: 20px;
                }

                .report-period{
                    font-size: 24px;
                    margin-bottom: 15px;
                }

                .report-period strong{
                    font-weight: 700;
                }

                .generated{
                    font-size: 16px;
                    color: #333;
                }

                .content{
                    padding: 35px 55px;
                }

                .detail-title{
                    font-size: 24px;
                    font-weight: 700;
                    margin-bottom: 15px;
                }

                .line{
                    height: 1px;
                    background: #C8CDD3;
                    margin-bottom: 25px;
                }

                table{
                    width: 100%;
                    border-collapse: collapse;
                    font-size: 15px;
                }

                th{
                    background: #8BC39A;
                    color: #111;
                    padding: 14px 10px;
                    border: 1px solid #9FB8A7;
                    text-align: center;
                    font-weight: 700;
                }

                td{
                    padding: 12px 10px;
                    border: 1px solid #B7C7BB;
                    vertical-align: top;
                }

                .empty{
                    text-align: center;
                    padding: 30px;
                }
                
            </style>
        </head>

        <body>

            <div class="header">
                <div class="brand">
                    <img src="' . $logoBase64 . '" alt="Logo">
                </div>

                <div class="title">
                    ' . htmlspecialchars($title) . '
                </div>

            </div>

            <div class="sub-header">

                <div class="report-period">
                    <strong>Laporan Bulanan</strong> | ' . htmlspecialchars($filterText) . '
                </div>

                <div class="generated">
                    Dibuat pada ' . $generatedAt . '
                </div>

            </div>

            <div class="content">

                <div class="detail-title">
                    ' . htmlspecialchars($detailTitle) . '
                </div>

                <div class="line"></div>

                <table>

                    <thead>
                        <tr>
                            ' . $tableHeaders . '
                        </tr>
                    </thead>

                    <tbody>
                        ' . $tableRows . '
                    </tbody>

                </table>

            </div>

        </body>
        </html>
        ';
    }

    private function buildDetailAnimalHtml(DetailAnimalReport $report, string $generatedAt): string
    {
        $animal = $report->getAnimal();
        $healthHistory = $report->getHealthHistory();
        $reproductionHistory = $report->getReproductionHistory();
        $lastUpdate = $this->formatPdfDate((string) ($animal['pemeriksaan_terakhir'] ?? ''), 'd M Y');
        $birthDate = $this->formatPdfDate((string) ($animal['tanggal_lahir'] ?? $animal['tgl_lahir'] ?? ''), 'd M Y');
        $periodLabel = $this->buildDetailPeriodLabel((string) ($animal['pemeriksaan_terakhir'] ?? ''));

        $logoPath = realpath(__DIR__ . '/../../public/images/logo/logo2.png');
        $logoBase64 = '';

        if ($logoPath && file_exists($logoPath)) {
            $imageType = pathinfo($logoPath, PATHINFO_EXTENSION);
            $imageData = base64_encode(file_get_contents($logoPath));
            $logoBase64 = 'data:image/' . $imageType . ';base64,' . $imageData;
        }

        $animalImage = $this->toBase64Image($animal['gambar'] ?? '');

        $reproductionRows = '';
        if ($reproductionHistory === []) {
            $reproductionRows = '<tr><td colspan="4" class="empty">Belum ada riwayat reproduksi.</td></tr>';
        } else {
            foreach ($reproductionHistory as $row) {
                $reproductionRows .= '<tr>'
                    . '<td>' . htmlspecialchars((string) ($row['tanggal_ib_label'] ?? '-')) . '</td>'
                    . '<td>' . htmlspecialchars((string) ($row['tgl_perkiraan_label'] ?? '-')) . '</td>'
                    . '<td>' . htmlspecialchars((string) ($row['hasil'] ?? '-')) . '</td>'
                    . '<td>' . htmlspecialchars((string) ($row['keterangan'] ?? '-')) . '</td>'
                    . '</tr>';
            }
        }

        $healthRows = '';
        if ($healthHistory === []) {
            $healthRows = '<tr><td colspan="3" class="empty">Belum ada riwayat pemeriksaan.</td></tr>';
        } else {
            foreach ($healthHistory as $row) {
                $healthRows .= '<tr>'
                    . '<td>' . htmlspecialchars((string) ($row['tanggal_label'] ?? '-')) . '</td>'
                    . '<td>' . htmlspecialchars((string) ($row['diagnosis'] ?? '-')) . '</td>'
                    . '<td>' . htmlspecialchars((string) ($row['tindakan'] ?? '-')) . '</td>'
                    . '</tr>';
            }
        }

        return '
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <style>
                body {
                    font-family: sans-serif;
                    color: #111111;
                    font-size: 13px;
                    margin: 0;
                    padding: 0;
                    background: #ffffff;
                }
                .page {
                    width: 100%;
                }
                .header-wrap {
                    padding: 48px 54px 34px;
                }
                .header-table {
                    width: 100%;
                    border-collapse: collapse;
                }
                .logo-cell {
                    width: 220px;
                    vertical-align: middle;
                }
                .logo-cell img {
                    width: 180px;
                    height: auto;
                }
                .title-cell {
                    vertical-align: middle;
                    text-align: left;
                }
                .title-cell h1 {
                    margin: 0;
                    font-size: 34px;
                    line-height: 1.15;
                    letter-spacing: 0.3px;
                    font-weight: 800;
                    text-transform: uppercase;
                    color: #111111;
                }
                .band {
                    background: #eef1f7;
                    padding: 26px 54px;
                }
                .band-title {
                    font-size: 24px;
                    font-weight: 800;
                    color: #111111;
                    margin: 0;
                }
                .band-title .light {
                    font-weight: 500;
                }
                .band-subtitle {
                    margin: 18px 0 0;
                    font-size: 18px;
                    color: #242424;
                }
                .content {
                    padding: 38px 54px 44px;
                }
                .animal-table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 28px;
                }
                .animal-table td {
                    border: none;
                    vertical-align: top;
                    padding: 0;
                }
                .animal-table td:first-child {
                    padding-right: 28px;
                }
                .animal-photo {
                    width: 270px;
                    height: 195px;
                    border-radius: 24px;
                    overflow: hidden;
                    background: #f2f4f7;
                }
                .animal-photo img {
                    width: 270px;
                    height: 195px;
                    object-fit: cover;
                }
                .animal-meta {
                    padding-left: 28px;
                }
                .meta-row {
                    margin-bottom: 12px;
                    font-size: 17px;
                    line-height: 1.35;
                }
                .meta-label {
                    display: inline-block;
                    width: 180px;
                    font-weight: 800;
                    color: #111111;
                }
                .meta-value {
                    font-weight: 500;
                    color: #222222;
                }
                .stats-table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 28px;
                }
                .stats-table td {
                    border: none;
                    padding: 16px 0;
                    vertical-align: top;
                    width: 50%;
                }
                .stat-label {
                    color: #111111;
                    font-size: 18px;
                    margin-bottom: 2px;
                    font-weight: 800;
                }
                .stat-value {
                    font-size: 18px;
                    font-weight: 500;
                    color: #222222;
                }
                .section-band {
                    background: #eef1f7;
                    padding: 18px 54px;
                    margin: 0 -54px 18px;
                    display: block;
                    width: calc(100% + 108px);
                    box-sizing: border-box;
                }
                .section-band h2 {
                    margin: 0;
                    font-size: 18px;
                    font-weight: 800;
                    color: #111111;
                }
                .section-title {
                    margin: 0 0 18px;
                    font-size: 18px;
                    font-weight: 800;
                    color: #111111;
                }
                table.report-table {
                    width: 100%;
                    border-collapse: collapse;
                    font-size: 12px;
                    margin-bottom: 32px;
                }
                table.report-table th {
                    background: #8fc89a;
                    color: #111111;
                    padding: 16px 14px;
                    text-align: center;
                    border: 1px solid #8fc89a;
                    font-size: 14px;
                    font-weight: 800;
                }
                table.report-table td {
                    padding: 16px 14px;
                    border: 1px solid #8fc89a;
                    color: #222222;
                    vertical-align: top;
                }
                .empty {
                    text-align: center;
                    color: #7d867d;
                }
                .note-title {
                    margin: 8px 0 14px;
                    font-size: 18px;
                    font-weight: 800;
                    color: #111111;
                }
                .note-text {
                    font-size: 15px;
                    color: #222222;
                    line-height: 1.7;
                    min-height: 80px;
                }
                .footer-text {
                    margin-top: 28px;
                    color: #8a8a8a;
                    font-size: 11px;
                }
            </style>
        </head>
        <body>
            <div class="page">
                <div class="header-wrap">
                    <table class="header-table">
                        <tr>
                            <td class="logo-cell">' . ($logoBase64 !== '' ? '<img src="' . $logoBase64 . '" alt="Logo">' : '') . '</td>
                            <td class="title-cell">
                                <h1>' . htmlspecialchars('Laporan ' . $report->getTitle()) . '</h1>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="band">
                    <p class="band-title"><span style="font-weight:800;">Laporan Bulanan</span> <span class="light">| ' . htmlspecialchars($periodLabel) . '</span></p>
                    <p class="band-subtitle">Dibuat pada ' . htmlspecialchars($generatedAt) . '</p>
                </div>

                <div class="content">
                    <table class="animal-table">
                        <tr>
                            <td style="width:270px;">
                                <div class="animal-photo">' . ($animalImage !== '' ? '<img src="' . $animalImage . '" alt="Foto ternak">' : '') . '</div>
                            </td>
                            <td class="animal-meta">
                                <div class="meta-row"><span class="meta-label">Kode Hewan:</span><span class="meta-value">' . htmlspecialchars((string) ($animal['kode'] ?? '-')) . '</span></div>
                                <div class="meta-row"><span class="meta-label">Jenis:</span><span class="meta-value">' . htmlspecialchars((string) ($animal['jenis'] ?? '-')) . '</span></div>
                                <div class="meta-row"><span class="meta-label">Berat (Kg):</span><span class="meta-value">' . htmlspecialchars((string) (($animal['berat'] ?? 0) > 0 ? rtrim(rtrim(number_format((float) $animal['berat'], 1, '.', ''), '0'), '.') : '-')) . '</span></div>
                                <div class="meta-row"><span class="meta-label">Jenis Kelamin:</span><span class="meta-value">' . htmlspecialchars((string) ($animal['jenis_kelamin'] ?? $animal['kelamin'] ?? '-')) . '</span></div>
                                <div class="meta-row"><span class="meta-label">Tanggal Lahir:</span><span class="meta-value">' . htmlspecialchars($birthDate) . '</span></div>
                                <div class="meta-row"><span class="meta-label">Umur:</span><span class="meta-value">' . htmlspecialchars((string) ($animal['umur'] ?? '-')) . '</span></div>
                                <div class="meta-row"><span class="meta-label">Lokasi:</span><span class="meta-value">' . htmlspecialchars((string) ($animal['kandang'] ?? '-')) . '</span></div>
                                <div class="meta-row"><span class="meta-label">Status:</span><span class="meta-value">' . htmlspecialchars((string) ($animal['status_kesehatan'] ?? '-')) . '</span></div>
                            </td>
                        </tr>
                    </table>

                    <div class="section-band">
                        <h2>Pemeriksaan Terakhir: ' . htmlspecialchars((string) ($animal['pemeriksaan_terakhir_label'] ?? '-')) . '</h2>
                    </div>

                    <table class="stats-table">
                        <tr>
                            <td>
                                <div class="stat-label">Total Pemeriksaan: <div class="stat-value">' . htmlspecialchars((string) ($animal['total_pemeriksaan'] ?? 0)) . '</div> </div>
                            </td>
                            <td>
                                <div class="stat-label">Total Reproduksi: <div class="stat-value">' . htmlspecialchars((string) ($animal['total_reproduksi'] ?? 0)) . '</div> </div>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="section-band">
                    <h2>Riwayat Kesehatan</h2>
                </div>

                <div class="content" style="padding-top: 6px;">
                    <h3 class="section-title">Riwayat Pemeriksaan Lengkap</h3>
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Diagnosis</th>
                                <th>Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>' . $healthRows . '</tbody>
                    </table>

                    <h3 class="section-title">Riwayat Reproduksi</h3>
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>Tanggal IB</th>
                                <th>Perkiraan Lahir</th>
                                <th>Hasil</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>' . $reproductionRows . '</tbody>
                    </table>

                    <div class="note-title">Catatan Medis</div>
                    <div class="note-text">' . nl2br(htmlspecialchars((string) ($animal['catatan_medis'] ?? '-'))) . '</div>

                    <div class="footer-text">
                        Terakhir Update : ' . htmlspecialchars($lastUpdate) . '
                    </div>
                </div>
            </div>
        </body>
        </html>';
    }

    private function toBase64Image(string $path): string
    {
        $trimmed = trim($path);
        if ($trimmed === '') {
            return '';
        }

        if (str_starts_with($trimmed, 'http://') || str_starts_with($trimmed, 'https://')) {
            return $trimmed;
        }

        $normalized = preg_replace('#^(\.\./|./)+#', '', str_replace('\\', '/', $trimmed)) ?? $trimmed;
        $normalized = ltrim($normalized, '/');

        if (!str_starts_with($normalized, 'public/') && !str_starts_with($normalized, 'uploads/')) {
            $normalized = 'uploads/hewan/' . basename($normalized);
        }

        $absolutePath = realpath(__DIR__ . '/../../' . $normalized);
        if (!$absolutePath || !file_exists($absolutePath)) {
            return '';
        }

        $imageType = pathinfo($absolutePath, PATHINFO_EXTENSION);
        $imageData = base64_encode(file_get_contents($absolutePath));

        return 'data:image/' . $imageType . ';base64,' . $imageData;
    }

    private function formatPdfDate(string $date, string $format): string
    {
        if ($date === '' || !strtotime($date)) {
            return '-';
        }

        $formatted = date($format, strtotime($date));
        $months = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember',
            'Jan' => 'Jan',
            'Feb' => 'Feb',
            'Mar' => 'Mar',
            'Apr' => 'Apr',
            'Jun' => 'Jun',
            'Jul' => 'Jul',
            'Aug' => 'Agu',
            'Sep' => 'Sep',
            'Oct' => 'Okt',
            'Nov' => 'Nov',
            'Dec' => 'Des',
        ];

        return strtr($formatted, $months);
    }

    private function buildDetailPeriodLabel(string $referenceDate): string
    {
        $timestamp = strtotime($referenceDate);
        if ($timestamp === false) {
            $timestamp = time();
        }

        $monthNames = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $month = $monthNames[(int) date('n', $timestamp)] ?? date('F', $timestamp);

        return $month . ' ' . date('Y', $timestamp);
    }
}

final class ManagerReportFactory
{
    public static function create(string $slug, mysqli $db, array $filters = []): AbstractManagerReport
    {
        $filterObject = new ManagerReportFilters($filters);

        return match ($slug) {
            'populasi' => new PopulationReport($db, $filterObject),
            'kesehatan' => new HealthReport($db, $filterObject),
            'transaksi' => new TransactionReport($db, $filterObject),
            'detail_hewan' => new DetailAnimalReport($db, $filterObject, (int) ($filters['animal_id'] ?? $filters['id'] ?? 0)),
            default => throw new InvalidArgumentException('Jenis laporan tidak dikenali.'),
        };
    }
}

final class ManagerExporterFactory
{
    public static function create(string $format): AbstractReportExporter
    {
        return match (strtolower($format)) {
            'pdf' => new PdfReportExporter(),
            default => throw new InvalidArgumentException('Format export tidak didukung.'),
        };
    }
}
