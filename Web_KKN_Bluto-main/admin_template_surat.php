<?php
session_start();

// Cek if admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

require __DIR__ . '/config/database.php';

$action = $_GET['action'] ?? '';
$type = $_GET['type'] ?? '';

// Handle form submission for updating template
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_template'])) {
    $tipe_surat = $_POST['tipe_surat'] ?? '';
    $template_html = $_POST['template_html'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';

    if (!empty($tipe_surat) && !empty($template_html)) {
        try {
            // Check if template exists
            $stmt = $koneksi->prepare("SELECT id FROM template_surat WHERE tipe_surat = ?");
            $stmt->execute([$tipe_surat]);
            $existing = $stmt->fetch();

            if ($existing) {
                // Update existing template
                $stmt = $koneksi->prepare("UPDATE template_surat SET template_html = ?, deskripsi = ?, updated_at = CURRENT_TIMESTAMP WHERE tipe_surat = ?");
                $stmt->execute([$template_html, $deskripsi, $tipe_surat]);
                $message = "Template berhasil diperbarui!";
                $message_type = "success";
            } else {
                // Insert new template
                $stmt = $koneksi->prepare("INSERT INTO template_surat (tipe_surat, template_html, deskripsi) VALUES (?, ?, ?)");
                $stmt->execute([$tipe_surat, $template_html, $deskripsi]);
                $message = "Template berhasil disimpan!";
                $message_type = "success";
            }
        } catch (PDOException $e) {
            $message = "Error: " . $e->getMessage();
            $message_type = "error";
        }
    } else {
        $message = "Tipe surat dan template tidak boleh kosong!";
        $message_type = "error";
    }
}

// Get all available letter types
$letter_types = [
    'nikah' => 'Surat Keterangan Nikah',
    'sku' => 'Surat Keterangan Usaha (SKU)',
    'sktm' => 'Surat Keterangan Tidak Mampu (SKTM)',
    'domisili' => 'Surat Keterangan Domisili (SKD)'
];

// Get current template if editing
$current_template = null;
if (!empty($type) && isset($letter_types[$type])) {
    try {
        $stmt = $koneksi->prepare("SELECT * FROM template_surat WHERE tipe_surat = ?");
        $stmt->execute([$type]);
        $current_template = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Ignore error
    }
}

// Get all templates list
try {
    $stmt = $koneksi->query("SELECT tipe_surat, deskripsi, updated_at FROM template_surat ORDER BY updated_at DESC");
    $all_templates = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $all_templates = [];
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - Template Surat</title>
    <!-- CKEditor 4 (no API key required) -->
    <script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 1400px; margin: 0 auto; }
        .header { background: #2b7de9; color: white; padding: 20px; margin-bottom: 20px; border-radius: 5px; }
        .header h1 { margin-bottom: 5px; }
        .header a { color: white; text-decoration: none; margin-top: 10px; display: inline-block; }
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 5px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .main-grid { display: grid; grid-template-columns: 250px 1fr; gap: 20px; }
        .sidebar { background: white; padding: 20px; border-radius: 5px; height: fit-content; }
        .sidebar h3 { margin-bottom: 15px; border-bottom: 2px solid #2b7de9; padding-bottom: 10px; font-size: 14px; }
        .template-list { list-style: none; }
        .template-list li { margin-bottom: 8px; }
        .template-list a { 
            display: block; 
            padding: 12px; 
            background: #f9f9f9; 
            text-decoration: none; 
            color: #333; 
            border-left: 3px solid #2b7de9; 
            border-radius: 3px; 
            transition: all 0.2s;
            font-size: 13px;
        }
        .template-list a:hover { background: #e3f2fd; padding-left: 15px; }
        .template-list a.active { background: #2b7de9; color: white; }
        .editor { background: white; padding: 20px; border-radius: 5px; }
        .editor h2 { margin-bottom: 20px; color: #2b7de9; font-size: 18px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; color: #333; font-size: 13px; }
        .form-group input[type="text"],
        .form-group select { 
            width: 100%; 
            padding: 10px; 
            border: 1px solid #ddd; 
            border-radius: 3px; 
            font-size: 13px;
        }
        .tox-tinymce { border: 1px solid #ddd !important; border-radius: 3px !important; }
        .button-group { display: flex; gap: 10px; margin-top: 20px; }
        .btn { padding: 10px 20px; border: none; border-radius: 3px; cursor: pointer; font-size: 14px; text-decoration: none; display: inline-block; }
        .btn-primary { background: #2b7de9; color: white; }
        .btn-primary:hover { background: #1a5fb8; }
        .btn-secondary { background: #6c757d; color: white; }
        .btn-secondary:hover { background: #5a6268; }
        .variables-help { background: #f0f8ff; padding: 12px; border-radius: 3px; margin-bottom: 15px; border-left: 4px solid #2b7de9; font-size: 12px; }
        .variables-help h4 { margin-bottom: 8px; color: #2b7de9; font-size: 13px; }
        .variables-list { display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 6px; }
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
        .tox .tox-toolbar { background: #f9f9f9; }
        @media (max-width: 768px) {
            .main-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✏️ Editor Format Surat (WYSIWYG)</h1>
            <p>Edit format surat dengan mudah seperti menggunakan Word</p>
            <a href="admin.php">← Kembali ke Admin</a>
        </div>

        <?php if (isset($message)): ?>
            <div class="alert alert-<?php echo $message_type; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <div class="main-grid">
            <!-- Sidebar - Daftar Template -->
            <div class="sidebar">
                <h3>📋 Jenis Surat</h3>
                <ul class="template-list">
                    <?php foreach ($letter_types as $key => $label): ?>
                        <li>
                            <a href="?action=edit&type=<?php echo $key; ?>" 
                               class="<?php echo ($type === $key) ? 'active' : ''; ?>">
                                <?php echo $label; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Main Editor -->
            <div class="editor">
                <?php if (!empty($type) && isset($letter_types[$type])): ?>
                    <h2>📝 <?php echo htmlspecialchars($letter_types[$type]); ?></h2>

                    <form method="POST">
                        <div class="form-group">
                            <label for="tipe_surat">Tipe Surat:</label>
                            <input type="hidden" name="tipe_surat" id="tipe_surat" value="<?php echo htmlspecialchars($type); ?>">
                            <input type="text" value="<?php echo htmlspecialchars($letter_types[$type]); ?>" disabled>
                        </div>

                        <div class="form-group">
                            <label for="deskripsi">Deskripsi (Opsional):</label>
                            <input type="text" name="deskripsi" id="deskripsi" 
                                   value="<?php echo htmlspecialchars($current_template['deskripsi'] ?? ''); ?>"
                                   placeholder="Contoh: Template standar untuk SKU tahun 2024">
                        </div>

                        <div class="variables-help">
                            <h4>💡 Variabel yang Tersedia:</h4>
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
                            <label for="template_html">Format Surat (Edit seperti Word):</label>
                            <textarea name="template_html" id="template_html" required><?php 
                                if ($current_template) {
                                    echo htmlspecialchars($current_template['template_html']);
                                } else {
                                    require __DIR__ . '/config/surat_helper.php';
                                    $default = generateSuratDraft($type, [
                                        'nama' => '{nama}',
                                        'nik' => '{nik}',
                                        'alamat' => '{alamat}',
                                        'nama_usaha' => '{nama_usaha}',
                                        'lokasi_usaha' => '{lokasi_usaha}',
                                        'keterangan' => '{keterangan}'
                                    ]);
                                    echo htmlspecialchars($default);
                                }
                            ?></textarea>
                        </div>

                        <div class="button-group">
                            <button type="submit" name="save_template" class="btn btn-primary">💾 Simpan Format</button>
                            <a href="admin_template_surat.php" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                <?php else: ?>
                    <div style="text-align: center; padding: 60px 20px; color: #999;">
                        <p style="font-size: 48px; margin-bottom: 15px;">📋</p>
                        <p style="font-size: 16px;">Pilih jenis surat dari daftar untuk mulai mengedit</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // Initialize CKEditor for template_html
        window.addEventListener('load', function() {
            if (document.getElementById('template_html')) {
                CKEDITOR.replace('template_html', {
                    height: 600
                });
                // Ensure textarea updated before submit
                document.querySelector('form').addEventListener('submit', function() {
                    for (var name in CKEDITOR.instances) {
                        CKEDITOR.instances[name].updateElement();
                    }
                });
            }
        });

        function insertVariable(variable) {
            // Insert at current cursor position
            var inst = CKEDITOR.instances['template_html'];
            if (inst) {
                inst.insertText(variable);
                inst.focus();
            } else {
                var ta = document.getElementById('template_html');
                if (ta) {
                    ta.value = ta.value + variable;
                    ta.focus();
                }
            }
        }
    </script>
</body>
</html>
