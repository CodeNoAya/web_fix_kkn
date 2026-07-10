-- 1. Buat Database dan Gunakan
CREATE DATABASE IF NOT EXISTS web_desa;
USE web_desa;

-- 2. Buat Tabel-Tabel
CREATE TABLE IF NOT EXISTS admin (
    id_admin INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    terakhir_login DATETIME NULL
);

CREATE TABLE IF NOT EXISTS profil_desa (
    id_profil INT PRIMARY KEY DEFAULT 1,
    nama_desa VARCHAR(100) NOT NULL,
    kepala_desa VARCHAR(100) NOT NULL,
    alamat_balai TEXT NOT NULL,
    email VARCHAR(100) NULL,
    telepon VARCHAR(20) NULL,
    visi TEXT NULL,
    misi TEXT NULL,
    sejarah TEXT NULL,
    foto_kades VARCHAR(255) NULL
);

CREATE TABLE IF NOT EXISTS kategori_berita (
    id_kategori INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS berita (
    id_berita INT AUTO_INCREMENT PRIMARY KEY,
    id_kategori INT NOT NULL,
    judul VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    isi TEXT NOT NULL,
    gambar_cover VARCHAR(255) NULL,
    tanggal_publikasi DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('draft', 'publish') DEFAULT 'publish',
    id_admin INT NOT NULL,
    FOREIGN KEY (id_kategori) REFERENCES kategori_berita(id_kategori) ON UPDATE CASCADE ON DELETE RESTRICT,
    FOREIGN KEY (id_admin) REFERENCES admin(id_admin) ON UPDATE CASCADE ON DELETE RESTRICT
);

-- 3. ISI DATA DUMMY UNTUK DITAMPILKAN DI WEBSITE

-- Buat 1 Akun Admin
INSERT INTO admin (id_admin, username, password, nama_lengkap) 
VALUES (1, 'admin', 'rahasia123', 'Admin KKN UTM 42');

-- Isi Profil Desa Bluto
INSERT INTO profil_desa (id_profil, nama_desa, kepala_desa, alamat_balai, email, telepon, visi) 
VALUES (1, 'Desa Bluto', 'Bapak Kepala Desa', 'Jl. Raya Bluto, Kec. Bluto, Kab. Sumenep, Jawa Timur', 'pemdes@bluto.desa.id', '081234567890', 'Mewujudkan Desa Bluto yang Maju, Sejahtera, dan Inovatif berbasis Potensi Lokal.');

-- Buat Kategori Berita
INSERT INTO kategori_berita (id_kategori, nama_kategori) 
VALUES (1, 'Pengumuman'), (2, 'Kegiatan KKN');

-- Buat 2 Berita Dummy
INSERT INTO berita (id_kategori, judul, slug, isi, id_admin) 
VALUES 
(2, 'Kerja Bakti Bersama Mahasiswa KKN UTM', 'kerja-bakti-kkn-utm', 'Pada hari minggu ini, mahasiswa KKN 42 UTM bersama warga Desa Bluto melakukan kegiatan kerja bakti membersihkan area balai desa.', 1),
(1, 'Penyaluran Bantuan Bibit Pertanian', 'bantuan-bibit-pertanian', 'Pemerintah desa mulai mendistribusikan program ketahanan pangan kepada para kelompok tani setempat.', 1);