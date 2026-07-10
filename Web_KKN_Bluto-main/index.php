<?php

require_once 'config/database.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

include 'templates/header.php';

switch ($page) {
    case 'home':
        include 'pages/home.php';
        break;
    case 'profil':
        include 'pages/profil.php';
        break;
    case 'berita':
        include 'pages/berita.php';
        break;
    case 'detail_berita':
        include 'pages/detail_berita.php';
        break;
    case 'galeri':
        include 'pages/Galeri.php';
        break;
    case 'layanan':                 
        include 'pages/layanan.php';
        break;
    case 'dapur-lokal':             
        include 'pages/dapur-lokal.php';
        break;
    case 'kontak':
        include 'pages/kontak.php';
        break;
    default:
        
        echo "<div class='container mt-5 py-5 text-center' style='min-height: 60vh;'>
                <h1 class='display-1 text-muted'><i class='bi bi-emoji-frown'></i></h1>
                <h2>404 - Halaman Tidak Ditemukan</h2>
                <p>Maaf, halaman yang Anda cari tidak ada atau sedang dalam perbaikan.</p>
                <a href='index.php' class='btn btn-success mt-3'>Kembali ke Beranda</a>
              </div>";
        break;
}

include 'templates/footer.php';
?>
