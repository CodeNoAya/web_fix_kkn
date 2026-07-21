<?php
require __DIR__ . '/config/surat_helper.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method not allowed']);
    exit;
}

$type = $_POST['type'] ?? 'nikah';
$data = [
    'nama' => trim($_POST['nama'] ?? ''),
    'nik' => trim($_POST['nik'] ?? ''),
    'alamat' => trim($_POST['alamat'] ?? ''),
    'type' => $type,
    'created_at' => date('c'),
];

$draft = generateSuratDraft($type, $data);

$submission = [
    'id' => uniqid('surat_', true),
    'data' => $data,
    'draft' => $draft,
    'status' => 'pending'
];

$path = __DIR__ . '/data/surat_submissions.json';
$all = [];
if (file_exists($path)) {
    $raw = file_get_contents($path);
    $all = json_decode($raw, true) ?: [];
}

$all[] = $submission;
if (!is_dir(__DIR__ . '/data')) {
    mkdir(__DIR__ . '/data', 0755, true);
}
file_put_contents($path, json_encode($all, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

header('Content-Type: application/json');
echo json_encode(['ok' => true, 'id' => $submission['id']]);

?>
