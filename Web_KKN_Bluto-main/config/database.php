<?php

$db_file = __DIR__ . '/web_desa.sqlite';

try {
    $koneksi = new PDO("sqlite:" . $db_file);
    $koneksi->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $koneksi->exec("CREATE TABLE IF NOT EXISTS visitor_logs (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        ip_address TEXT,
        user_agent TEXT,
        page TEXT,
        referer TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    try {
        $columns = $koneksi->query("PRAGMA table_info(profil_desa)")->fetchAll(PDO::FETCH_ASSOC);
        $columnNames = array_column($columns, 'name');
        if (!in_array('foto_kades', $columnNames, true)) {
            $koneksi->exec("ALTER TABLE profil_desa ADD COLUMN foto_kades TEXT");
        }
        if (!in_array('masa_jabatan', $columnNames, true)) {
            $koneksi->exec("ALTER TABLE profil_desa ADD COLUMN masa_jabatan TEXT");
        }
        if (!in_array('misi', $columnNames, true)) {
            $koneksi->exec("ALTER TABLE profil_desa ADD COLUMN misi TEXT");
        }
    } catch (PDOException $e) {
        // Abaikan jika tabel profil_desa belum dibuat atau migrasi tidak diperlukan
    }

    // Buat tabel jenis_surat untuk menyimpan daftar jenis surat yang tersedia
    try {
        $koneksi->exec("CREATE TABLE IF NOT EXISTS jenis_surat (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nama TEXT UNIQUE
        )");

        // Isi default beberapa jenis surat bila belum ada
        $koneksi->exec("INSERT OR IGNORE INTO jenis_surat (nama) VALUES
            ('Surat Keterangan Usaha (SKU)'),
            ('Surat Keterangan Tidak Mampu (SKTM)'),
            ('Surat Keterangan Domisili (SKD)')");
    } catch (PDOException $e) {
        // Jika gagal membuat/menyisipkan, abaikan agar aplikasi tetap berjalan
    }

    // Buat tabel template_surat untuk menyimpan custom template surat
    try {
        $koneksi->exec("CREATE TABLE IF NOT EXISTS template_surat (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            tipe_surat TEXT UNIQUE NOT NULL,
            template_html TEXT NOT NULL,
            deskripsi TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");
    } catch (PDOException $e) {
        // Jika gagal membuat tabel, abaikan agar aplikasi tetap berjalan
    }
} catch(PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
?>
