<?php
require __DIR__ . '/../config/database.php';
try {
    $tipe = $argv[1] ?? 'sku';
    $html = $argv[2] ?? '<div><h2>Template Uji {nama}</h2><p>Ini contoh template untuk {tipe}.</p></div>';
    $des = 'Template uji otomatis';

    $stmt = $koneksi->prepare('INSERT OR REPLACE INTO template_surat (id, tipe_surat, template_html, deskripsi, created_at, updated_at) VALUES ((SELECT id FROM template_surat WHERE tipe_surat = ?), ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)');
    $stmt->execute([$tipe, $tipe, $html, $des]);
    echo "Inserted/Updated template: $tipe\n";
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}
