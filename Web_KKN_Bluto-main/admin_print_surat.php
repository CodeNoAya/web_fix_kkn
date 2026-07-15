<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/surat_helper.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    echo 'ID pengajuan tidak valid.';
    exit;
}

try {
    $stmt = $koneksi->prepare('SELECT * FROM pengajuan_surat WHERE id_pengajuan = :id');
    $stmt->execute([':id' => $id]);
    $s = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Gagal mengambil data pengajuan: ' . $e->getMessage();
    exit;
}

if (!$s) {
    echo 'Pengajuan tidak ditemukan.';
    exit;
}

// Tentukan tipe template dari nama jenis_surat
$jenis = strtolower($s['jenis_surat'] ?? '');
if (strpos($jenis, 'nikah') !== false) {
    $type = 'nikah';
} elseif (strpos($jenis, 'usaha') !== false || strpos($jenis, 'sku') !== false) {
    $type = 'sku';
} elseif (strpos($jenis, 'tidak mampu') !== false || strpos($jenis, 'sktm') !== false) {
    $type = 'sktm';
} elseif (strpos($jenis, 'domisili') !== false || strpos($jenis, 'skd') !== false) {
    $type = 'skd';
} else {
    $type = 'default';
}

$data = [
    'nama' => $s['nama_pemohon'] ?? '',
    'nik' => $s['nik'] ?? '',
    'alamat' => $s['keperluan'] ?? $s['alamat'] ?? '',
    // allow extra fields for certain templates if stored in DB
    'nama_usaha' => $s['nama_usaha'] ?? '',
    'lokasi_usaha' => $s['lokasi_usaha'] ?? '',
    'keterangan' => $s['keterangan'] ?? '',
    'status_tinggal' => $s['status_tinggal'] ?? ''
];

$draft = generateSuratDraft($type, $data);

?><!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cetak Surat - <?= htmlspecialchars($s['nama_pemohon']) ?></title>
    <style>body{font-family:serif; background:white; color:#000}</style>
</head>
<body>
    <?php echo $draft; ?>
    <script>
        // panggil dialog cetak setelah halaman siap
        window.addEventListener('load', function(){ setTimeout(function(){ window.print(); }, 200); });
    </script>
</body>
</html>
