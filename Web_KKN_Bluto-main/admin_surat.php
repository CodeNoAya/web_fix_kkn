<?php
require __DIR__ . '/config/surat_helper.php';

$path = __DIR__ . '/data/surat_submissions.json';
$all = file_exists($path) ? json_decode(file_get_contents($path), true) : [];

$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? '';

if ($action && $id) {
    foreach ($all as &$s) {
        if ($s['id'] === $id) {
            if ($action === 'approve') {
                $s['status'] = 'approved';
            } elseif ($action === 'delete') {
                $s['status'] = 'deleted';
            } elseif ($action === 'print') {
                // render printable draft and auto-open print dialog
                echo "<!doctype html><html><head><meta charset='utf-8'><title>Cetak Surat</title><style>body{font-family:serif;}</style></head><body>";
                echo $s['draft'];
                echo "<script>setTimeout(function(){ window.print(); }, 300);</script></body></html>";
                exit;
            }
            break;
        }
    }
    file_put_contents($path, json_encode($all, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    header('Location: admin_surat.php');
    exit;
}

?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin - Pengajuan Surat</title>
  <style>
    table{border-collapse:collapse;width:100%}
    th,td{border:1px solid #ddd;padding:8px}
    th{background:#f4f4f4}
    .btn{display:inline-block;padding:6px 10px;margin-right:6px;background:#2b7de9;color:#fff;text-decoration:none;border-radius:3px}
    .btn-danger{background:#d9534f}
    .status-pending{color:#d79b00}
    .status-approved{color:green}
  </style>
</head>
<body>
  <h2>Daftar Pengajuan Surat</h2>
  <p>Perangkat desa cukup <strong>memeriksa</strong> data lalu tekan <strong>Setujui</strong> atau <strong>Cetak</strong>.</p>

  <table>
    <tr><th>ID</th><th>Nama</th><th>NIK</th><th>Jenis</th><th>Tanggal</th><th>Status</th><th>Aksi</th></tr>
    <?php foreach ($all as $s):
      if ($s['status'] === 'deleted') continue;
      $d = $s['data'];
    ?>
      <tr>
        <td><?php echo $s['id']; ?></td>
        <td><?php echo htmlspecialchars($d['nama']); ?></td>
        <td><?php echo htmlspecialchars($d['nik']); ?></td>
        <td><?php echo htmlspecialchars($d['type']); ?></td>
        <td><?php echo htmlspecialchars($d['created_at']); ?></td>
        <td class="status-<?php echo $s['status']; ?>"><?php echo htmlspecialchars($s['status']); ?></td>
        <td>
          <a class="btn" href="admin_surat.php?action=approve&id=<?php echo $s['id']; ?>">Setujui</a>
          <a class="btn" href="admin_surat.php?action=print&id=<?php echo $s['id']; ?>" target="_blank">Cetak</a>
          <a class="btn btn-danger" href="admin_surat.php?action=delete&id=<?php echo $s['id']; ?>">Hapus</a>
        </td>
      </tr>
      <tr><td colspan="7">
        <strong>Draft Surat:</strong>
        <div style="border:1px dashed #ccc;padding:8px;margin-top:6px;background:#fff"><?php echo $s['draft']; ?></div>
      </td></tr>
    <?php endforeach; ?>
  </table>

</body>
</html>
