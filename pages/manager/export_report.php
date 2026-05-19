<?php

require_once __DIR__ . '/manager_bootstrap.php';

$reportSlug = trim((string) ($_GET['report'] ?? ''));
$format = trim((string) ($_GET['format'] ?? 'pdf'));

try {
    $report = manager_make_report($reportSlug, $_GET);
    $exporter = manager_make_exporter($format);
    $exporter->download($report);
} catch (Throwable $exception) {
    http_response_code(400);
    echo 'Export laporan gagal: ' . htmlspecialchars($exception->getMessage(), ENT_QUOTES, 'UTF-8');
}
