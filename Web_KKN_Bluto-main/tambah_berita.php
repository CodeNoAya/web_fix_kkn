<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$error_msg = '';

try {
    $stmtKategori = $koneksi->query("SELECT * FROM kategori_berita ORDER BY nama_kategori ASC");
    $kategoriList = $stmtKategori->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $kategoriList = [];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = trim($_POST['judul']);
    $id_kategori = isset($_POST['id_kategori']) ? (int)$_POST['id_kategori'] : 0;
    $new_kategori = trim($_POST['new_kategori'] ?? '');
    $isi = trim($_POST['isi']);
    $gambar_cover = '';

    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];
        $fileName = basename($_FILES['gambar']['name']);
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $maxSize = 2 * 1024 * 1024;

        if (!in_array($fileExt, $allowedExt, true)) {
            $error_msg = 'Format gambar tidak didukung. Gunakan JPG, PNG, atau WEBP.';
        } elseif ($_FILES['gambar']['size'] > $maxSize) {
            $error_msg = 'Ukuran gambar terlalu besar. Maksimal 2 MB.';
        } else {
            $uploadDir = __DIR__ . '/assets/img/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $newFileName = time() . '_' . uniqid() . '.' . $fileExt;
            $targetPath = $uploadDir . $newFileName;

            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $targetPath)) {
                $gambar_cover = $newFileName;
            } else {
                $error_msg = 'Gagal mengunggah gambar.';
            }
        }
    } elseif (isset($_FILES['gambar']) && $_FILES['gambar']['error'] !== UPLOAD_ERR_NO_FILE) {
        $error_msg = 'Terjadi kesalahan saat mengunggah gambar.';
    }

    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $judul)));

    if ($new_kategori !== '') {
        try {
            $stmtExisting = $koneksi->prepare("SELECT id_kategori FROM kategori_berita WHERE LOWER(nama_kategori) = LOWER(:nama) LIMIT 1");
            $stmtExisting->execute([':nama' => $new_kategori]);
            $existing = $stmtExisting->fetch(PDO::FETCH_ASSOC);
            if ($existing) {
                $id_kategori = $existing['id_kategori'];
            } else {
                $stmtInsertKat = $koneksi->prepare("INSERT INTO kategori_berita (nama_kategori) VALUES (:nama)");
                $stmtInsertKat->execute([':nama' => $new_kategori]);
                $id_kategori = $koneksi->lastInsertId();
            }
        } catch (PDOException $e) {
            $error_msg = 'Gagal menyimpan kategori baru: ' . $e->getMessage();
        }
    }

    if ($judul !== '' && $isi !== '' && $id_kategori > 0 && $error_msg === '') {
        try {
            $query = "INSERT INTO berita (id_kategori, judul, slug, isi, gambar_cover, id_admin) VALUES (:kategori, :judul, :slug, :isi, :gambar_cover, 1)";
            $stmt = $koneksi->prepare($query);
            $stmt->execute([
                ':kategori' => $id_kategori,
                ':judul' => $judul,
                ':slug' => $slug,
                ':isi' => $isi,
                ':gambar_cover' => $gambar_cover
            ]);

            
            header("Location: admin.php");
            exit;
        } catch (PDOException $e) {
            $error_msg = "Gagal mempublikasikan berita: " . $e->getMessage();
        }
    } else {
        $error_msg = "Harap isi semua kolom formulir dengan benar!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tulis Berita Baru - Admin Desa</title>
    
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --navy-dark: #0f172a;
            --navy-medium: #1e293b;
            --emerald-primary: #10b981;
            --emerald-hover: #059669;
            --bg-light-gray: #f8fafc;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-light-gray);
            color: #1e293b;
        }

        .navbar-admin {
            background-color: var(--navy-dark);
            border-bottom: 3px solid var(--emerald-primary);
        }

        .form-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
        }

        .form-control-custom {
            border: 1px solid #cbd5e1;
            padding: 12px 16px;
            border-radius: 10px;
            transition: all 0.2s ease;
        }
        .form-control-custom:focus {
            border-color: var(--emerald-primary);
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.25);
        }
        
        .btn-emerald {
            background-color: var(--emerald-primary);
            color: white;
            font-weight: 600;
            padding: 12px 20px;
            border-radius: 10px;
            border: none;
            transition: all 0.2s ease;
        }
        .btn-emerald:hover {
            background-color: var(--emerald-hover);
            color: white;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }
    </style>
</head>
<body class="pb-5">

    
    <nav class="navbar navbar-dark navbar-admin py-3 shadow-sm mb-4">
        <div class="container">
            <span class="navbar-brand mb-0 h1 fw-bold d-flex align-items-center">
                <i class="bi bi-pencil-square text-success me-2"></i>Tulis Berita Baru
            </span>
            <a href="admin.php" class="btn btn-outline-light btn-sm rounded-pill px-3">
                <i class="bi bi-arrow-left me-1"></i> Batal & Kembali
            </a>
        </div>
    </nav>

    <div class="container" style="max-width: 800px;">
        
        
        <?php if($error_msg): ?>
            <div class="alert alert-danger border-0 shadow-sm rounded-3 py-3 px-4 mb-4">
                <i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i><?= $error_msg ?>
            </div>
        <?php endif; ?>

        
        <div class="card form-card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-4 p-md-5">
                
                <form action="tambah_berita.php" method="POST" enctype="multipart/form-data">
                    
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted mb-1.5">Judul Berita</label>
                        <input type="text" name="judul" class="form-control form-control-custom" 
                               placeholder="Masukkan judul berita yang menarik" required autofocus>
                    </div>
                    
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted mb-1.5">Kategori Berita</label>
                        <select name="id_kategori" class="form-select form-control-custom">
                            <option value="" selected>-- Pilih Kategori yang Ada --</option>
                            <?php foreach ($kategoriList as $kategori): ?>
                                <option value="<?= $kategori['id_kategori'] ?>"><?= htmlspecialchars($kategori['nama_kategori']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text text-muted small mt-1.5">Pilih kategori yang sudah tersedia atau tambahkan kategori baru di bawah.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted mb-1.5">Tambah Kategori Baru (opsional)</label>
                        <input type="text" name="new_kategori" class="form-control form-control-custom" placeholder="Contoh: Agenda Desa" value="<?= htmlspecialchars($_POST['new_kategori'] ?? '') ?>">
                        <div class="form-text text-muted small mt-1.5">Jika kategori belum ada, ketik nama baru lalu publikasi. Kategori baru otomatis ditambahkan.</div>
                    </div>
                    
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted mb-1.5">Gambar Cover (opsional)</label>
                        <input type="file" name="gambar" class="form-control form-control-custom" accept="image/*">
                        <div class="form-text text-muted small mt-1.5">Unggah gambar maksimal 2 MB. Format: JPG, PNG, atau WEBP.</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted mb-1.5">Isi Berita</label>
                        <textarea name="isi" class="form-control form-control-custom" rows="12" 
                                  placeholder="Ketik isi berita lengkap di sini..." required></textarea>
                        <div class="form-text text-muted small mt-1.5">Gunakan tombol Enter untuk memisahkan antar paragraf.</div>
                    </div>
                    
                    
                    <button type="submit" class="btn btn-emerald w-100 py-2.5">
                        <i class="bi bi-send-fill me-1"></i> Publikasikan Berita
                    </button>
                    
                </form>
                
            </div>
        </div>
        
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
