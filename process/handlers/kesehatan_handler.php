    <?php
    ob_start();
    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $root_dir = dirname(__DIR__, 2);
    require_once $root_dir . '/config/database.php';
    require_once $root_dir . '/process/models/kesehatan.php';
    require_once $root_dir . '/process/models/reproduksi.php';

    $db_conn = new Database();
    $connection = $db_conn->getConnection();

    $kesehatan_model = new Kesehatan($connection);
    $reproduksi_model = new Reproduksi($connection);

    function safe_redirect($path)
    {
        if (ob_get_level()) ob_end_clean();
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
        $url = $protocol . "://" . $_SERVER['HTTP_HOST'] . $path;
        header("Location: " . $url);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['action'])) {
        safe_redirect('/HayFarm/pages/admin/data_kesehatan.php');
    }

    try {
        $action = $_POST['action'];

        switch ($action) {
            case 'create':
                // ✅ 1. Validasi input user (Handler responsibility)
                if (empty($_POST['id_hewan'])) throw new Exception('Hewan wajib dipilih');
                if (empty($_POST['tgl_pemeriksaan'])) throw new Exception('Tanggal pemeriksaan wajib diisi');
                if (empty($_POST['status_kesehatan'])) throw new Exception('Status kesehatan wajib dipilih');

                // ✅ 2. Normalisasi status (Handler responsibility)
                $status_raw = $_POST['status_kesehatan'];
                $status_normalized = match ($status_raw) {
                    'sehat' => 'sehat',
                    'observasi', 'dalam_observasi' => 'observasi',  // ✅ Normalize ke 'observasi'
                    'perawatan', 'dalam_perawatan' => 'perawatan',   // ✅ Normalize ke 'perawatan'
                    default => 'sehat'
                };

                // ✅ 3. Validasi conditional
                if ($status_normalized !== 'sehat') {
                    if (trim($_POST['diagnosis'] ?? '') === '') {
                        throw new Exception("Diagnosis wajib diisi untuk status '" . ucfirst($status_normalized) . "'.");
                    }
                    if (trim($_POST['tindakan'] ?? '') === '') {
                        throw new Exception("Tindakan wajib diisi untuk status '" . ucfirst($status_normalized) . "'.");
                    }
                }

                // ✅ 4. Siapkan data bersih untuk model
                $data_kesehatan = [
                    'id_hewan' => (int)$_POST['id_hewan'],
                    'tgl_pemeriksaan' => $_POST['tgl_pemeriksaan'],
                    'status_kesehatan' => $status_normalized,
                    'diagnosis' => trim($_POST['diagnosis'] ?? ''),
                    'tindakan' => trim($_POST['tindakan'] ?? ''),
                    'catatan' => trim($_POST['catatan'] ?? '')
                ];

                // ✅ 5. START TRANSACTION - semua operasi DB di dalam sini
                $connection->begin_transaction();
                try {
                    // 1. Simpan Kesehatan
                    $result_kesehatan = $kesehatan_model->create($data_kesehatan);
                    if (!$result_kesehatan['status']) throw new Exception($result_kesehatan['message']);

                    // 2. Cek apakah ada data IB yang diisi
                    $has_ib_data = !empty($_POST['tgl_ib']) && !empty($_POST['ib_ke']);

                    if ($has_ib_data) {
                        $new_id_kesehatan = $connection->insert_id;

                        // Normalisasi status_ib
                        $status_ib_raw = $_POST['status_ib'] ?? '';
                        $status_ib_normalized = match ($status_ib_raw) {
                            'berhasil' => 'berhasil',
                            'tdk_berhasil', 'tidak_berhasil' => 'tdk_berhasil',
                            'proses' => 'proses',
                            default => null
                        };

                        $data_reproduksi = [
                            'id_kesehatan' => $new_id_kesehatan,
                            'id_hewan' => (int)$_POST['id_hewan'],
                            'tgl_ib' => $_POST['tgl_ib'],
                            'ib_ke' => (int)$_POST['ib_ke'],
                            'tgl_perkiraan' => $_POST['tgl_perkiraan'] ?? null,
                            'status_ib' => $status_ib_normalized
                        ];

                        $result_reproduksi = $reproduksi_model->create($data_reproduksi);
                        if (!$result_reproduksi['status']) throw new Exception($result_reproduksi['message']);
                    }

                    // 3. Commit jika semua sukses
                    $connection->commit();
                    $_SESSION['flash_type'] = 'success';
                    $_SESSION['flash_message'] = 'Data kesehatan dan reproduksi berhasil disimpan!';
                } catch (Exception $e) {
                    // Rollback jika ada error
                    $connection->rollback();
                    throw new Exception($e->getMessage());
                }
                break;

            case 'update':
                $id = filter_input(INPUT_POST, 'id_kesehatan', FILTER_VALIDATE_INT);
                if (!$id) throw new Exception('ID kesehatan tidak valid');

                // Validasi status kesehatan
                $status_raw = $_POST['status_kesehatan'] ?? '';
                $status_normalized = match ($status_raw) {
                    'sehat' => 'sehat',
                    'observasi', 'dalam_observasi' => 'observasi',  // ✅ Normalize ke 'observasi'
                    'perawatan', 'dalam_perawatan' => 'perawatan',   // ✅ Normalize ke 'perawatan'
                    default => 'sehat'
                };

                if ($status_normalized !== 'sehat') {
                    if (trim($_POST['diagnosis'] ?? '') === '') {
                        throw new Exception("Diagnosis wajib diisi untuk status '" . ucfirst($status_normalized) . "'.");
                    }
                    if (trim($_POST['tindakan'] ?? '') === '') {
                        throw new Exception("Tindakan wajib diisi untuk status '" . ucfirst($status_normalized) . "'.");
                    }
                }

                // 1. Update data kesehatan dulu
                $data_kesehatan = [
                    'id_hewan' => (int)($_POST['id_hewan'] ?? 0),
                    'tgl_pemeriksaan' => $_POST['tgl_pemeriksaan'] ?? '',
                    'status_kesehatan' => $status_normalized,
                    'diagnosis' => trim($_POST['diagnosis'] ?? ''),
                    'tindakan' => trim($_POST['tindakan'] ?? ''),
                    'catatan' => trim($_POST['catatan'] ?? '')
                ];

                $result_kesehatan = $kesehatan_model->update($id, $data_kesehatan);
                if (!$result_kesehatan['status']) throw new Exception($result_kesehatan['message']);

                // 2. Cek apakah ada data IB yang diisi (untuk update/create reproduksi)
                $has_ib_data = !empty($_POST['tgl_ib']) && !empty($_POST['ib_ke']);

                if ($has_ib_data) {
                    // Cek apakah sudah ada record reproduksi untuk id_kesehatan ini
                    $existing_reproduksi = $reproduksi_model->getByKesehatanId($id);

                    if ($existing_reproduksi) {
                        // ✅ UPDATE existing reproduksi
                        $data_reproduksi = [
                            'id_kesehatan' => $id,
                            'id_hewan' => (int)($_POST['id_hewan'] ?? 0),
                            'tgl_ib' => $_POST['tgl_ib'],
                            'ib_ke' => (int)$_POST['ib_ke'],
                            'tgl_perkiraan' => $_POST['tgl_perkiraan'] ?? null,
                            'status_ib' => $_POST['status_ib'] ?? null
                        ];

                        $result_reproduksi = $reproduksi_model->update($id, $data_reproduksi);
                        if (!$result_reproduksi['status']) throw new Exception($result_reproduksi['message']);
                    } else {
                        // ✅ CREATE baru jika belum ada
                        $data_reproduksi = [
                            'id_kesehatan' => $id,
                            'id_hewan' => (int)($_POST['id_hewan'] ?? 0),
                            'tgl_ib' => $_POST['tgl_ib'],
                            'ib_ke' => (int)$_POST['ib_ke'],
                            'tgl_perkiraan' => $_POST['tgl_perkiraan'] ?? null,
                            'status_ib' => $_POST['status_ib'] ?? null
                        ];

                        $result_reproduksi = $reproduksi_model->create($data_reproduksi);
                        if (!$result_reproduksi['status']) throw new Exception($result_reproduksi['message']);
                    }
                }

                $_SESSION['flash_type'] = 'success';
                $_SESSION['flash_message'] = 'Data kesehatan dan reproduksi berhasil diperbarui!';
                break;
                $id = filter_input(INPUT_POST, 'id_kesehatan', FILTER_VALIDATE_INT);
                if (!$id) throw new Exception('ID tidak valid');

                $status = $_POST['status_kesehatan'] ?? '';
                if ($status !== 'sehat') {
                    if (trim($_POST['diagnosis'] ?? '') === '') {
                        throw new Exception("Diagnosis wajib diisi untuk status '{$status}'.");
                    }
                    if (trim($_POST['tindakan'] ?? '') === '') {
                        throw new Exception("Tindakan wajib diisi untuk status '{$status}'.");
                    }
                }

                $data = [
                    'id_hewan' => (int)($_POST['id_hewan'] ?? 0),
                    'tgl_pemeriksaan' => $_POST['tgl_pemeriksaan'] ?? '',
                    'status_kesehatan' => $status,
                    'diagnosis' => trim($_POST['diagnosis'] ?? ''),
                    'tindakan' => trim($_POST['tindakan'] ?? ''),
                    'catatan' => trim($_POST['catatan'] ?? '')
                ];

                $result = $kesehatan_model->update($id, $data);
                if ($result['status']) {
                    $_SESSION['flash_type'] = 'success';
                    $_SESSION['flash_message'] = $result['message'];
                } else {
                    throw new Exception($result['message']);
                }
                break;

            case 'delete':
                $id = filter_input(INPUT_POST, 'id_kesehatan', FILTER_VALIDATE_INT);
                if (!$id) throw new Exception('ID kesehatan tidak valid');

                $result = $kesehatan_model->delete($id);
                if ($result['status']) {
                    $_SESSION['flash_type'] = 'success';
                    $_SESSION['flash_message'] = $result['message'];
                } else {
                    throw new Exception($result['message']);
                }
                break;

            default:
                throw new Exception('Aksi tidak dikenali');
        }

        if ($result['status']) {
            $_SESSION['flash_type'] = 'success';
            $_SESSION['flash_message'] = $result['message'];
        } else {
            throw new Exception($result['message']);
        }
    } catch (Exception $e) {
        $_SESSION['flash_type'] = 'error';
        $_SESSION['flash_message'] = $e->getMessage();
    }

    safe_redirect('/HayFarm/pages/admin/data_kesehatan.php');
