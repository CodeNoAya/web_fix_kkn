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
    *{margin:0;padding:0;box-sizing:border-box}
    body{font-family:Arial,sans-serif;background:#f5f5f5;padding:20px}
    .container{max-width:1200px;margin:0 auto}
    .header{background:#2b7de9;color:white;padding:20px;margin-bottom:20px;border-radius:5px}
    .header h2{margin-bottom:10px}
    .header p{margin-bottom:10px;opacity:0.95}
    .toolbar{display:flex;gap:10px;margin-bottom:20px;flex-wrap:wrap}
    table{border-collapse:collapse;width:100%;background:white;border-radius:5px;overflow:hidden}
    th,td{border:1px solid #ddd;padding:12px;text-align:left}
    th{background:#f4f4f4;font-weight:bold}
    tr:hover{background:#f9f9f9}
    .btn{display:inline-block;padding:8px 12px;margin-right:6px;background:#2b7de9;color:#fff;text-decoration:none;border-radius:3px;border:none;cursor:pointer;font-size:13px}
    .btn:hover{background:#1a5fb8}
    .btn-danger{background:#d9534f}
    .btn-danger:hover{background:#c9302c}
    .btn-secondary{background:#6c757d}
    .btn-secondary:hover{background:#5a6268}
    .btn-warning{background:#f0ad4e}
    .btn-warning:hover{background:#ec971f}
    .status-pending{color:#d79b00;font-weight:bold}
    .status-approved{color:green;font-weight:bold}
    .draft-container{background:#f9f9f9;padding:12px;margin-top:8px;border:1px dashed #ccc;border-radius:3px;max-height:200px;overflow-y:auto;font-size:13px}
    .action-buttons{display:flex;gap:6px;flex-wrap:wrap}
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h2>📋 Daftar Pengajuan Surat</h2>
      <p>Perangkat desa bisa memeriksa data, mengedit draft, dan menyetujui pengajuan surat</p>
    </div>

    <div class="toolbar">
      <a href="admin.php" class="btn btn-secondary">← Kembali</a>
      <a href="admin_template_surat.php" class="btn btn-warning">⚙️ Kelola Template Surat</a>
    </div>

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
          <div class="action-buttons">
            <a class="btn" href="edit_surat_draft.php?id=<?php echo $s['id']; ?>">✏️ Edit</a>
            <a class="btn" href="admin_surat.php?action=approve&id=<?php echo $s['id']; ?>">✓ Setujui</a>
            <a class="btn" href="admin_surat.php?action=print&id=<?php echo $s['id']; ?>" target="_blank">🖨️ Cetak</a>
            <a class="btn btn-danger" href="admin_surat.php?action=delete&id=<?php echo $s['id']; ?>">🗑️ Hapus</a>
          </div>
        </td>
      </tr>
      <tr><td colspan="7">
        <strong>📄 Draft Surat:</strong>
        <div class="draft-container"><?php echo $s['draft']; ?></div>
      </td></tr>
    <?php endforeach; ?>
  </table>
  </div>

</body>
</html>
