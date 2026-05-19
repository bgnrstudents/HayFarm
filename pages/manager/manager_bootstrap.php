<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../process/models/manager_reports.php';

if (!function_exists('manager_count_by_key')) {
    function manager_count_by_key(array $items, string $key): array
    {
        $result = [];

        foreach ($items as $item) {
            $label = $item[$key] ?? 'Lainnya';
            $result[$label] = ($result[$label] ?? 0) + 1;
        }

        return $result;
    }

    function manager_format_currency(int|float $amount): string
    {
        return 'Rp ' . number_format((float) $amount, 0, ',', '.');
    }

    function manager_format_date(string $date, string $format = 'd M Y'): string
    {
        $datetime = DateTime::createFromFormat('Y-m-d', $date);
        if (!$datetime) {
            return $date;
        }

        $formatted = $datetime->format($format);
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
            'May' => 'Mei',
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

    function manager_badge_class(string $status): string
    {
        $normalized = strtolower(trim($status));

        return match ($normalized) {
            'aktif', 'sehat', 'selesai', 'berhasil', 'produktif' => 'bg-success',
            'bunting', 'diproses', 'dalam perawatan', 'pemantauan', 'proses ib' => 'bg-warning text-dark',
            'sakit', 'tidak berhasil' => 'bg-danger',
            'terjual', 'observasi', 'menunggu' => 'bg-info text-dark',
            'tidak produktif' => 'bg-secondary',
            default => 'bg-secondary',
        };
    }

    function manager_json(mixed $value): string
    {
        $encoded = json_encode(
            $value,
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
        );

        return $encoded !== false ? $encoded : 'null';
    }

    function manager_escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    function manager_make_report(string $slug, array $filters = []): AbstractManagerReport
    {
        $database = new Database();
        return ManagerReportFactory::create($slug, $database->getConnection(), $filters);
    }

    function manager_make_exporter(string $format): AbstractReportExporter
    {
        return ManagerExporterFactory::create($format);
    }

    function manager_get_reproduction_history(int $animalId): array
    {
        $database = new Database();
        $reproduksiModel = new Reproduksi($database->getConnection());
        $rows = $reproduksiModel->getByHewanId($animalId);

        return array_map(static function (array $row): array {
            return [
                'id' => (int) ($row['id_hewan'] ?? 0),
                'tanggal_ib' => (string) ($row['tgl_ib'] ?? ''),
                'tgl_perkiraan' => (string) ($row['tgl_perkiraan'] ?? ''),
                'petugas' => '-',
                'hasil' => match ((string) ($row['status_ib'] ?? '')) {
                    'berhasil' => 'Berhasil',
                    'tdk_berhasil' => 'Tidak Berhasil',
                    'proses' => 'Proses IB',
                    default => 'Belum Ada',
                },
                'keterangan' => !empty($row['ib_ke']) ? 'IB ke-' . $row['ib_ke'] : '-',
            ];
        }, $rows);
    }
}
