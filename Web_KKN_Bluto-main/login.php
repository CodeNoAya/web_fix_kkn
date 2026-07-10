<?php

session_start();
require_once 'config/database.php';

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: admin.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    
    try {
        $stmt = $koneksi->prepare("SELECT * FROM admin WHERE username = :username AND password = :password LIMIT 1");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        
        if ($admin) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['nama_admin'] = $admin['nama_lengkap'];
            
            
            header("Location: admin.php");
            exit;
        } else {
            $error = 'Username atau password salah!';
        }
    } catch (PDOException $e) {
        $error = 'Koneksi database gagal: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrasi - Portal Desa Bluto</title>
    
    
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
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: radial-gradient(circle at top right, #1e293b 0%, #0f172a 100%);
            color: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            max-width: 420px;
            width: 100%;
            border-radius: 24px;
            background-color: rgba(30, 41, 59, 0.75);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.35), 0 0 50px rgba(16, 185, 129, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
            overflow: hidden;
        }

        .login-header {
            background: linear-gradient(180deg, rgba(15, 23, 42, 0.4) 0%, rgba(15, 23, 42, 0) 100%);
            padding: 40px 30px 20px 30px;
            text-align: center;
        }

        .form-control {
            background-color: rgba(15, 23, 42, 0.5);
            border: 2px solid #475569;
            color: #f8fafc;
            padding: 12px 16px;
            border-radius: 12px;
            transition: all 0.2s ease;
            font-size: 15px;
        }

        .form-control:focus {
            background-color: rgba(15, 23, 42, 0.7);
            border-color: var(--emerald-primary);
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.2);
            color: #f8fafc;
        }

        .form-control::placeholder {
            color: #64748b;
        }

        .input-group-text {
            background-color: rgba(15, 23, 42, 0.5);
            border: 2px solid #475569;
            border-right: none;
            color: #94a3b8;
            border-radius: 12px;
            padding-left: 18px;
            padding-right: 12px;
        }

        .input-group .form-control {
            border-left: none;
        }

        .input-group:focus-within .input-group-text {
            border-color: var(--emerald-primary);
            color: var(--emerald-primary);
        }

        .btn-emerald {
            background: linear-gradient(135deg, var(--emerald-primary) 0%, var(--emerald-hover) 100%);
            border: none;
            color: #ffffff;
            font-weight: 700;
            padding: 14px 20px;
            border-radius: 12px;
            transition: all 0.25s ease;
            letter-spacing: 0.5px;
        }

        .btn-emerald:hover {
            background: linear-gradient(135deg, var(--emerald-hover) 0%, #047857 100%);
            color: #ffffff;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
            transform: translateY(-1.5px);
        }

        .link-back {
            color: #94a3b8;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: color 0.2s ease;
        }

        .link-back:hover {
            color: var(--emerald-primary);
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-header">
        <img src="assets/img/logo_sumenep.png" alt="Logo Kabupaten Sumenep" class="mb-3" style="height: 80px; filter: drop-shadow(0px 2px 6px rgba(0,0,0,0.3));">
        <h4 class="fw-bold mb-1 text-white">Panel Administrasi</h4>
        <p class="mb-0 text-white-50 small">Pusat kendali portal Desa Bluto</p>
    </div>
    <div class="card-body p-4 p-md-5 pt-0">
        
        <?php if($error): ?>
            <div class="alert alert-danger py-2.5 small border-0 bg-danger bg-opacity-20 text-danger rounded-3 mb-4">
                <i class="bi bi-exclamation-triangle-fill me-2"></i><?= $error ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            
            <div class="mb-3">
                <label class="form-label fw-bold text-light small mb-2">Username</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="text" name="username" class="form-control" placeholder="Masukkan username" required autofocus>
                </div>
            </div>
            
            
            <div class="mb-4">
                <label class="form-label fw-bold text-light small mb-2">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-key"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan kata sandi" required>
                </div>
            </div>
            
            
            <button type="submit" class="btn btn-emerald w-100 mb-3">
                Masuk ke Dashboard <i class="bi bi-box-arrow-in-right ms-1"></i>
            </button>
        </form>
        
        
        <div class="text-center mt-4">
            <a href="index.php" class="link-back">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Website Warga
            </a>
        </div>
    </div>
</div>

</body>
</html>
