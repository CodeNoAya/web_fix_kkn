<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Resmi Desa Bluto - Kecamatan Bluto, Kabupaten Sumenep</title>
    
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    
    <style>
        :root {
            --navy-dark: #0f172a;
            --navy-medium: #1e293b;
            --navy-light: #475569;
            --emerald-primary: #10b981;
            --emerald-hover: #059669;
            --accent-yellow: #f59e0b;
            --bg-light-gray: #f8fafc;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-light-gray);
            color: #2b2d42;
            overflow-x: hidden;
        }

        
        .top-bar {
            background-color: #050b14;
            color: #e0e1dd;
            font-size: 12px;
            font-weight: 500;
            border-bottom: 2px solid var(--emerald-primary);
        }
        .top-bar a {
            color: #e0e1dd;
            text-decoration: none;
            transition: color 0.2s ease;
        }
        .top-bar a:hover {
            color: var(--accent-yellow);
        }

        
        .sidebar-left {
            background: linear-gradient(180deg, var(--navy-dark) 0%, #090d16 100%);
            min-height: 100vh;
        }

        @media (min-width: 992px) {
            .sidebar-left {
                position: fixed;
                top: 0;
                left: 0;
                width: 260px;
                height: 100vh;
                overflow-y: auto;
                z-index: 1030;
                border-right: 3px solid var(--emerald-primary);
            }
            .main-content-right {
                margin-left: 260px;
                width: calc(100% - 260px);
                flex: 0 0 calc(100% - 260px);
                max-width: calc(100% - 260px);
                margin-top: 40px; 
            }
            .top-bar {
                position: fixed;
                top: 0;
                right: 0;
                width: calc(100% - 260px);
                z-index: 1020;
            }
        }
        
        .logo-container {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .logo-img {
            max-height: 75px;
            filter: drop-shadow(0px 2px 5px rgba(0,0,0,0.5));
            transition: transform 0.3s ease;
        }
        .logo-img:hover {
            transform: scale(1.05);
        }

        
        .sidebar-nav-title {
            color: rgba(255, 255, 255, 0.4);
            font-size: 11px;
            font-weight: 700;
            text-uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            padding-bottom: 5px;
        }

        .sidebar-menu-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .sidebar-menu-item {
            margin-bottom: 4px;
        }
        .sidebar-menu-link {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            color: #94a3b8;
            text-decoration: none;
            border-radius: 8px;
            font-size: 13.5px;
            font-weight: 500;
            transition: all 0.25s ease;
            margin-bottom: 2px;
            border-left: 3px solid transparent;
        }
        .sidebar-menu-link i {
            margin-right: 12px;
            font-size: 17px;
            color: #64748b;
            transition: color 0.25s ease;
        }
        .sidebar-menu-link:hover {
            background-color: rgba(255, 255, 255, 0.04);
            color: #f8fafc;
        }
        .sidebar-menu-link:hover i {
            color: var(--emerald-primary);
        }
        .sidebar-menu-link.active {
            background-color: rgba(16, 185, 129, 0.08);
            color: #f8fafc;
            font-weight: 600;
            border-left: 3px solid var(--emerald-primary);
        }
        .sidebar-menu-link.active i {
            color: var(--emerald-primary);
        }

        
        @media (max-width: 991.98px) {
            body {
                padding-bottom: 70px;
            }
        }
        
        .bottom-nav-link {
            transition: all 0.2s ease;
            position: relative;
            padding: 4px 0;
            border-radius: 8px;
        }
        .bottom-nav-link:active {
            transform: scale(0.95);
        }
        .bottom-nav-link.active-pill {
            color: var(--emerald-primary) !important;
        }
        .bottom-nav-link.active-pill::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 50%;
            transform: translateX(-50%);
            width: 18px;
            height: 3px;
            background-color: var(--emerald-primary);
            border-radius: 2px;
        }

        .hover-link:hover {
            color: var(--accent-yellow) !important;
        }

        
        .widget-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            background-color: #ffffff;
            overflow: hidden;
            margin-bottom: 20px;
        }
        .widget-header {
            background: linear-gradient(135deg, var(--navy-dark) 0%, var(--navy-medium) 100%);
            color: #ffffff;
            padding: 14px 18px;
            font-weight: 600;
            font-size: 15px;
            border-bottom: 3px solid var(--emerald-primary);
            display: flex;
            align-items: center;
        }
        .widget-header i {
            margin-right: 8px;
            color: var(--accent-yellow);
        }
        .widget-body {
            padding: 18px;
        }

        
        .hover-lift {
            transition: transform 0.25s ease, box-shadow 0.25s ease !important;
        }
        .hover-lift:hover {
            transform: translateY(-4px) !important;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08) !important;
        }
    </style>
</head>
<body>

<div class="top-bar py-2">
    <div class="container-fluid px-3 px-lg-4">
        <div class="row align-items-center">
            <div class="col-8 col-md-6 d-flex align-items-center">
                <span class="me-3 small d-none d-sm-inline">
                    <i class="bi bi-calendar3 text-success me-1"></i>
                    <span id="current-date">Memuat tanggal...</span>
                </span>
                <span class="small">
                    <i class="bi bi-clock text-success me-1"></i>
                    <span id="digital-clock" class="fw-bold">Memuat jam...</span>
                </span>
            </div>
            <div class="col-4 col-md-6 text-end">
                <a href="login.php" class="small fw-semibold">
                    <i class="bi bi-shield-lock-fill text-success me-1"></i>Admin Panel
                </a>
            </div>
        </div>
    </div>
</div>

<nav class="navbar navbar-dark sticky-top shadow-sm d-lg-none py-2" style="background: linear-gradient(135deg, var(--navy-dark) 0%, #090d16 100%); border-bottom: 2px solid var(--emerald-primary);">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold d-flex align-items-center" href="index.php?page=home">
            <img src="assets/img/logo_sumenep.png" alt="Logo Kabupaten Sumenep" class="me-2" style="height: 32px; filter: drop-shadow(0px 1px 3px rgba(0,0,0,0.3));">
            <div>
                <span class="d-block lh-1 fs-5 text-white">Desa Bluto</span>
                <span style="font-size: 10px; font-weight: 400;" class="text-white-50">Kecamatan Bluto, Sumenep</span>
            </div>
        </a>
        
        
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#menuTambahanHP">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="menuTambahanHP">
            <ul class="navbar-nav ms-auto pt-2">
                <li class="nav-item"><a class="nav-link <?= ($page == 'home') ? 'active fw-bold' : ''; ?>" href="index.php?page=home"><i class="bi bi-house-door-fill me-2"></i>Beranda</a></li>
                <li class="nav-item"><a class="nav-link <?= ($page == 'profil') ? 'active fw-bold' : ''; ?>" href="index.php?page=profil"><i class="bi bi-info-circle-fill me-2"></i>Profil Desa</a></li>
                <li class="nav-item"><a class="nav-link <?= ($page == 'berita') ? 'active fw-bold' : ''; ?>" href="index.php?page=berita"><i class="bi bi-newspaper me-2"></i>Berita & Kegiatan</a></li>
                <li class="nav-item"><a class="nav-link <?= ($page == 'layanan') ? 'active fw-bold' : ''; ?>" href="index.php?page=layanan"><i class="bi bi-file-earmark-text-fill me-2"></i>Layanan Publik</a></li>
                <li class="nav-item"><a class="nav-link <?= ($page == 'dapur-lokal') ? 'active fw-bold' : ''; ?>" href="index.php?page=dapur-lokal"><i class="bi bi-shop me-2"></i>Dapur Lokal</a></li>
                <li class="nav-item"><a class="nav-link <?= ($page == 'galeri') ? 'active fw-bold' : ''; ?>" href="index.php?page=galeri"><i class="bi bi-images me-2"></i>Galeri Foto</a></li>
                <li class="nav-item"><a class="nav-link <?= ($page == 'kontak') ? 'active fw-bold' : ''; ?>" href="index.php?page=kontak"><i class="bi bi-telephone-fill me-2"></i>Hubungi Kami</a></li>
            </ul>
        </div>
    </div>
</nav>

<nav class="navbar fixed-bottom bg-white border-top d-lg-none shadow-lg py-2 z-3">
    <div class="container-fluid px-1">
        <div class="d-flex justify-content-around w-100">
            <a href="index.php?page=home" class="text-decoration-none text-center bottom-nav-link w-100 <?= ($page == 'home') ? 'active-pill' : 'text-secondary'; ?>">
                <i class="bi bi-house-door-fill fs-4 d-block mb-1"></i><span style="font-size: 11px; font-weight: 600;">Beranda</span>
            </a>
            <a href="index.php?page=profil" class="text-decoration-none text-center bottom-nav-link w-100 <?= ($page == 'profil') ? 'active-pill' : 'text-secondary'; ?>">
                <i class="bi bi-info-circle-fill fs-4 d-block mb-1"></i><span style="font-size: 11px; font-weight: 600;">Profil</span>
            </a>
            <a href="index.php?page=berita" class="text-decoration-none text-center bottom-nav-link w-100 <?= ($page == 'berita') ? 'active-pill' : 'text-secondary'; ?>">
                <i class="bi bi-newspaper fs-4 d-block mb-1"></i><span style="font-size: 11px; font-weight: 600;">Berita</span>
            </a>
            <a href="index.php?page=layanan" class="text-decoration-none text-center bottom-nav-link w-100 <?= ($page == 'layanan') ? 'active-pill' : 'text-secondary'; ?>">
                <i class="bi bi-file-earmark-text-fill fs-4 d-block mb-1"></i><span style="font-size: 11px; font-weight: 600;">Layanan</span>
            </a>
            <a href="index.php?page=dapur-lokal" class="text-decoration-none text-center bottom-nav-link w-100 <?= ($page == 'dapur-lokal') ? 'active-pill' : 'text-secondary'; ?>">
                <i class="bi bi-shop fs-4 d-block mb-1"></i><span style="font-size: 11px; font-weight: 600;">Dapur</span>
            </a>
        </div>
    </div>
</nav>

<div class="container-fluid min-vh-100 p-0 d-flex flex-column">
    <div class="d-flex flex-grow-1 flex-column flex-lg-row">
        
        
        <aside class="sidebar-left d-none d-lg-block col-lg-3 text-white py-4 px-3">
            
            <div class="logo-container text-center pb-3 mb-4">
                <img src="assets/img/logo_sumenep.png" alt="Logo Kabupaten Sumenep" class="logo-img img-fluid mb-2" style="width: 60px;">
                <h5 class="fw-bold mb-0 text-white tracking-wide">Pemerintah Desa Bluto</h5>
                <small class="text-white-50 small">Kecamatan Bluto, Sumenep</small>
            </div>

            
            <div class="sidebar-nav-title">Navigasi Utama</div>
            <ul class="sidebar-menu-list">
                <li class="sidebar-menu-item">
                    <a href="index.php?page=home" class="sidebar-menu-link <?= ($page == 'home') ? 'active' : ''; ?>">
                        <i class="bi bi-house-door-fill"></i> Beranda Utama
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="index.php?page=profil" class="sidebar-menu-link <?= ($page == 'profil') ? 'active' : ''; ?>">
                        <i class="bi bi-info-circle-fill"></i> Profil Desa
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="index.php?page=berita" class="sidebar-menu-link <?= ($page == 'berita') ? 'active' : ''; ?>">
                        <i class="bi bi-newspaper"></i> Berita & Kegiatan
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="index.php?page=layanan" class="sidebar-menu-link <?= ($page == 'layanan') ? 'active' : ''; ?>">
                        <i class="bi bi-file-earmark-text-fill"></i> Layanan Publik
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="index.php?page=dapur-lokal" class="sidebar-menu-link <?= ($page == 'dapur-lokal') ? 'active' : ''; ?>">
                        <i class="bi bi-shop"></i> Dapur Lokal (UMKM)
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="index.php?page=galeri" class="sidebar-menu-link <?= ($page == 'galeri') ? 'active' : ''; ?>">
                        <i class="bi bi-images"></i> Galeri Foto
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="index.php?page=kontak" class="sidebar-menu-link <?= ($page == 'kontak') ? 'active' : ''; ?>">
                        <i class="bi bi-envelope-fill"></i> Kontak & Peta
                    </a>
                </li>
            </ul>
        </aside>

        
        <div class="col-12 col-lg-9 bg-light d-flex flex-column justify-content-between main-content-right">
            <main class="container-fluid py-4 px-lg-4">
