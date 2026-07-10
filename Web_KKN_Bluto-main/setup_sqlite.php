<?php
require_once 'config/database.php';

$query = "
CREATE TABLE IF NOT EXISTS admin (
    id_admin INTEGER PRIMARY KEY AUTOINCREMENT, 
    username TEXT, 
    password TEXT, 
    nama_lengkap TEXT
);

CREATE TABLE IF NOT EXISTS profil_desa (
    id_profil INTEGER PRIMARY KEY DEFAULT 1, 
    nama_desa TEXT, 
    kepala_desa TEXT, 
    foto_kades TEXT, 
    visi TEXT
);

CREATE TABLE IF NOT EXISTS kategori_berita (
    id_kategori INTEGER PRIMARY KEY AUTOINCREMENT, 
    nama_kategori TEXT
);

CREATE TABLE IF NOT EXISTS berita (
    id_berita INTEGER PRIMARY KEY AUTOINCREMENT, 
    id_kategori INTEGER, 
    judul TEXT, 
    slug TEXT, 
    isi TEXT, 
    gambar_cover TEXT, 
    tanggal_publikasi DATETIME DEFAULT CURRENT_TIMESTAMP, 
    id_admin INTEGER
);

CREATE TABLE IF NOT EXISTS pengajuan_surat (
    id_pengajuan INTEGER PRIMARY KEY AUTOINCREMENT,
    nama_pemohon TEXT,
    nik TEXT,
    no_hp TEXT,
    jenis_surat TEXT,
    keperluan TEXT,
    status_pengajuan TEXT DEFAULT 'Menunggu',
    tanggal_pengajuan DATETIME DEFAULT CURRENT_TIMESTAMP,
    nomor_registrasi TEXT
);

CREATE TABLE IF NOT EXISTS umkm (
    id_produk INTEGER PRIMARY KEY AUTOINCREMENT,
    nama TEXT,
    kategori TEXT,
    harga INTEGER,
    penjual TEXT,
    gambar TEXT,
    no_wa TEXT
);

CREATE TABLE IF NOT EXISTS galeri (
    id_galeri INTEGER PRIMARY KEY AUTOINCREMENT,
    judul TEXT,
    gambar TEXT,
    tanggal TEXT
);

CREATE TABLE IF NOT EXISTS aspirasi (
    id_aspirasi INTEGER PRIMARY KEY AUTOINCREMENT,
    nama_lengkap TEXT,
    kontak TEXT,
    isi_pesan TEXT,
    tanggal_kirim DATETIME DEFAULT CURRENT_TIMESTAMP
);

INSERT OR IGNORE INTO admin (id_admin, username, password, nama_lengkap) 
VALUES (1, 'admin', 'rahasia123', 'Admin KKN UTM 42');

INSERT OR IGNORE INTO profil_desa (id_profil, nama_desa, kepala_desa, visi) 
VALUES (1, 'Desa Bluto', 'Bapak Kepala Desa', 'Mewujudkan Desa Bluto yang Maju dan Sejahtera.');

INSERT OR IGNORE INTO kategori_berita (id_kategori, nama_kategori) 
VALUES (1, 'Pengumuman'), (2, 'Kegiatan KKN');

INSERT OR IGNORE INTO berita (id_kategori, judul, slug, isi, id_admin) 
VALUES (2, 'Kerja Bakti KKN UTM', 'kerja-bakti-kkn-utm', 'Kegiatan pembersihan balai desa.', 1);

INSERT OR IGNORE INTO umkm (id_produk, nama, kategori, harga, penjual, gambar, no_wa) VALUES
(1, 'Keripik Singkong Balado', 'Makanan Ringan', 15000, 'Ibu Siti (Dusun Utara)', 'https://images.unsplash.com/photo-1621939514649-280e2ee25f60?auto=format&fit=crop&q=60&w=500', '6281234567890'),
(2, 'Kopi Bubuk Asli Bluto', 'Minuman', 25000, 'Pak Budi M.', 'https://images.unsplash.com/photo-1559525839-b184a4d698c7?auto=format&fit=crop&q=60&w=500', '6281234567890'),
(3, 'Kerajinan Anyaman Bambu', 'Kriya & Kerajinan', 45000, 'Kelompok Tani Mekar', 'https://images.unsplash.com/photo-1606760227091-3dd870d97f1d?auto=format&fit=crop&q=60&w=500', '6281234567890'),
(4, 'Sambal Teri Pedas Nampol', 'Bahan Pokok', 20000, 'Dapur Bu Rina', 'https://images.unsplash.com/photo-1596649299486-4cdea56fd59d?auto=format&fit=crop&q=60&w=500', '6281234567890');

INSERT OR IGNORE INTO galeri (id_galeri, judul, gambar, tanggal) VALUES
(1, 'Panen Raya Kelompok Tani \"Maju Jaya\"', 'https://images.unsplash.com/photo-1596404987178-57448834db95?auto=format&fit=crop&q=80&w=600&h=400', '12 Juni 2026'),
(2, 'Kerja Bakti Rutin Warga Dusun 1', 'https://images.unsplash.com/photo-1517048676732-d65bc937f952?auto=format&fit=crop&q=80&w=600&h=400', '20 Mei 2026'),
(3, 'Musyawarah Perencanaan Pembangunan (Musrenbangdes)', 'https://images.unsplash.com/photo-1592861214088-7a5ceb1551a8?auto=format&fit=crop&q=80&w=600&h=400', '15 April 2026'),
(4, 'Kegiatan Posyandu Balita & Lansia Dusun Barat', 'https://images.unsplash.com/photo-1605000797499-95a51c5269ae?auto=format&fit=crop&q=80&w=600&h=400', '5 April 2026'),
(5, 'Pentas Seni Tari Tradisional KKN UTM', 'https://images.unsplash.com/photo-1589923188900-85dae523342b?auto=format&fit=crop&q=80&w=600&h=400', '17 Agustus 2025'),
(6, 'Pemandangan Sawah Terasing di Pagi Hari', 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&q=80&w=600&h=400', 'Dokumentasi KKN');
";

try {
    $koneksi->exec($query);
    echo "<div style='font-family: sans-serif; text-align: center; margin-top: 50px;'>";
    echo "<h1 style='color: green;'>SUKSES!</h1>";
    echo "<p>Database SQLite beserta tabelnya berhasil dibuat.</p>";
    echo "<a href='index.php' style='padding: 10px 20px; background-color: #198754; color: white; text-decoration: none; border-radius: 5px;'>Kembali ke Beranda</a>";
    echo "</div>";
} catch (PDOException $e) {
    echo "Gagal: " . $e->getMessage();
}
?>
