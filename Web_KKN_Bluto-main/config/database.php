<?php

$db_file = __DIR__ . '/web_desa.sqlite';

try {
    
    $koneksi = new PDO("sqlite:" . $db_file);
    
    
    $koneksi->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
?>
