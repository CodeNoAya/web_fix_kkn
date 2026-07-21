<?php
session_start();

// Cek if admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

require __DIR__ . '/config/surat_helper.php';

$path = __DIR__ . '/data/surat_submissions.json';
$all = file_exists($path) ? json_decode(file_get_contents($path), true) : [];

$id = $_GET['id'] ?? '';
$action = $_POST['action'] ?? '';

// Find the submission
$submission = null;
$submission_index = -1;
foreach ($all as $idx => $s) {
    if ($s['id'] === $id) {
        $submission = $s;
        $submission_index = $idx;
        break;
    }
}

if (!$submission) {
    die("Surat tidak ditemukan!");
}

// Handle save
if ($action === 'save_draft' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_draft = $_POST['draft'] ?? '';
    if (!empty($new_draft)) {
        $all[$submission_index]['draft'] = $new_draft;
        $all[$submission_index]['draft_edited_at'] = date('Y-m-d H:i:s');
        file_put_contents($path, json_encode($all, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $message = "Draft surat berhasil diperbarui!";
        $submission['draft'] = $new_draft;
    } else {
        $message = "Draft tidak boleh kosong!";
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Draft Surat</title>
    <!-- CKEditor 4 (no API key required) -->
    <script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        .header { background: #2b7de9; color: white; padding: 20px; margin-bottom: 20px; border-radius: 5px; }
        .header h1 { margin-bottom: 10px; }
        .header p { margin-bottom: 10px; opacity: 0.9; }
        .breadcrumb { display: inline-block; margin-top: 10px; }
        .breadcrumb a { color: white; text-decoration: none; }
        .breadcrumb a:hover { text-decoration: underline; }
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 5px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .info-box { background: white; padding: 15px; margin-bottom: 20px; border-radius: 5px; border-left: 4px solid #2b7de9; display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px; }
        .info-row { display: grid; grid-template-columns: 120px 1fr; gap: 10px; }
        .info-row strong { color: #333; font-weight: bold; }
        .info-row span { color: #666; }
        .editor-container { background: white; padding: 20px; border-radius: 5px; }
        .button-group { display: flex; gap: 10px; margin-top: 20px; }
        .btn { padding: 10px 20px; border: none; border-radius: 3px; cursor: pointer; font-size: 14px; text-decoration: none; display: inline-block; }
        .btn-primary { background: #2b7de9; color: white; }
        .btn-primary:hover { background: #1a5fb8; }
        .btn-secondary { background: #6c757d; color: white; }
        .btn-secondary:hover { background: #5a6268; }
        .btn-success { background: #28a745; color: white; }
        .btn-success:hover { background: #218838; }
        .tox-tinymce { border: 1px solid #ddd !important; border-radius: 3px !important; }
        .tox .tox-toolbar { background: #f9f9f9; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: bold; color: #333; font-size: 14px; }
        .variables-help { background: #f0f8ff; padding: 12px; border-radius: 3px; margin-bottom: 15px; border-left: 4px solid #2b7de9; font-size: 12px; }
        .variables-help h4 { margin-bottom: 8px; color: #2b7de9; }
        .variables-list { display: grid; grid-template-columns: repeat(auto-fill, minmax(110px, 1fr)); gap: 6px; }
        .variable-item { 
            background: white; 
            padding: 8px; 
            border: 1px solid #2b7de9; 
            border-radius: 3px; 
            font-family: monospace; 
            font-size: 11px; 
            cursor: pointer;
            text-align: center;
            transition: all 0.2s;
        }
        .variable-item:hover { background: #e3f2fd; transform: translateY(-2px); }
        @media (max-width: 768px) {
            .info-box { grid-template-columns: 1fr; }
            .button-group { flex-direction: column; }
            .btn { width: 100%; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✏️ Edit Draft Surat</h1>
            <p>Sesuaikan format dan isi surat dengan mudah seperti menggunakan Word</p>
            <div class="breadcrumb">
                <a href="admin.php">Admin</a> / 
                <a href="admin_surat.php">Daftar Pengajuan</a> / 
                <strong>Edit Draft</strong>
            </div>
        </div>

        <?php if (isset($message)): ?>
            <div class="alert alert-success">
                ✓ <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <div class="info-box">
            <div class="info-row">
                <strong>ID Pengajuan:</strong>
                <span><?php echo htmlspecialchars($submission['id']); ?></span>
            </div>
            <div class="info-row">
                <strong>Nama Pemohon:</strong>
                <span><?php echo htmlspecialchars($submission['data']['nama'] ?? '-'); ?></span>
            </div>
            <div class="info-row">
                <strong>NIK:</strong>
                <span><?php echo htmlspecialchars($submission['data']['nik'] ?? '-'); ?></span>
            </div>
            <div class="info-row">
                <strong>Jenis Surat:</strong>
                <span><?php echo htmlspecialchars($submission['data']['type'] ?? '-'); ?></span>
            </div>
            <div class="info-row">
                <strong>Tanggal Pengajuan:</strong>
                <span><?php echo htmlspecialchars($submission['data']['created_at'] ?? '-'); ?></span>
            </div>
            <div class="info-row">
                <strong>Status:</strong>
                <span style="color: <?php echo $submission['status'] === 'approved' ? 'green' : '#d79b00'; ?>; font-weight: bold;">
                    <?php echo htmlspecialchars($submission['status']); ?>
                </span>
            </div>
        </div>

        <div class="editor-container">
            <form method="POST">
                <input type="hidden" name="action" value="save_draft">

                <div class="variables-help">
                    <h4>💡 Klik untuk menyisipkan Variabel:</h4>
                    <div class="variables-list">
                        <div class="variable-item" onclick="insertVariable('{nama}')">{nama}</div>
                        <div class="variable-item" onclick="insertVariable('{nik}')">{nik}</div>
                        <div class="variable-item" onclick="insertVariable('{alamat}')">{alamat}</div>
                        <div class="variable-item" onclick="insertVariable('{tanggal}')">{tanggal}</div>
                        <div class="variable-item" onclick="insertVariable('{nama_usaha}')">{nama_usaha}</div>
                        <div class="variable-item" onclick="insertVariable('{lokasi_usaha}')">{lokasi_usaha}</div>
                        <div class="variable-item" onclick="insertVariable('{keterangan}')">{keterangan}</div>
                        <div class="variable-item" onclick="insertVariable('{kepala_desa}')">{kepala_desa}</div>
                        <div class="variable-item" onclick="insertVariable('{nama_desa}')">{nama_desa}</div>
                        <div class="variable-item" onclick="insertVariable('{nama_kecamatan}')">{nama_kecamatan}</div>
                        <div class="variable-item" onclick="insertVariable('{nama_kabupaten}')">{nama_kabupaten}</div>
                        <div class="variable-item" onclick="insertVariable('{status_tinggal}')">{status_tinggal}</div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="draft">📄 Format Surat (Edit seperti Word):</label>
                    <textarea name="draft" id="draft" required><?php echo htmlspecialchars($submission['draft']); ?></textarea>
                </div>

                <div class="button-group">
                    <button type="submit" class="btn btn-success">💾 Simpan Perubahan</button>
                    <button type="button" onclick="resetForm()" class="btn btn-secondary">↺ Reset</button>
                    <a href="admin_surat.php" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        const originalDraft = document.getElementById('draft').value;

        // Initialize CKEditor for draft textarea
        window.addEventListener('load', function() {
            if (document.getElementById('draft')) {
                CKEDITOR.replace('draft', { height: 600 });
                // Ensure textarea updated before submit
                document.querySelector('form').addEventListener('submit', function() {
                    for (var name in CKEDITOR.instances) {
                        CKEDITOR.instances[name].updateElement();
                    }
                });
            }
        });

        function insertVariable(variable) {
            var inst = CKEDITOR.instances['draft'];
            if (inst) {
                inst.insertText(variable);
                inst.focus();
            } else {
                var ta = document.getElementById('draft');
                if (ta) {
                    ta.value = ta.value + variable;
                    ta.focus();
                }
            }
        }

        function resetForm() {
            var inst = CKEDITOR.instances['draft'];
            if (inst) {
                inst.setData(originalDraft);
            } else {
                document.getElementById('draft').value = originalDraft;
                updatePreview();
            }
        }
    </script>
</body>
</html>
