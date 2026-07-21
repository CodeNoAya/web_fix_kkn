<?php
require __DIR__ . '/../config/database.php';
try {
    $stmt = $koneksi->query("SELECT tipe_surat, length(template_html) as len, updated_at FROM template_surat ORDER BY updated_at DESC LIMIT 10");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($rows) === 0) {
        echo "No templates found\n";
    } else {
        foreach ($rows as $r) {
            echo $r['tipe_surat'] . " — len=" . $r['len'] . " — updated=" . $r['updated_at'] . "\n";
        }
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}
