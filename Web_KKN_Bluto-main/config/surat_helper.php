<?php
function generateSuratDraft($type, $data) {
    $tanggal_sekarang = date('d F Y');
    $nama = htmlspecialchars($data['nama'] ?? '');
    $nik = htmlspecialchars($data['nik'] ?? '');
    $alamat = nl2br(htmlspecialchars($data['alamat'] ?? ''));

    // Default wilayah (user requested Sumenep / Desa Bluto)
    $nama_kabupaten = 'SUMENEP';
    $nama_kecamatan = 'Bluto';
    $nama_desa = 'Desa Bluto';
    $kepala_desa = 'Kepala Desa';

    // Coba ambil dari database profil_desa jika tersedia
    $custom_template = null;
    $dbFile = __DIR__ . '/database.php';
    if (file_exists($dbFile)) {
        try {
            require_once $dbFile; // provides $koneksi (PDO)
            if (isset($koneksi)) {
                try {
                    $stmt = $koneksi->query("SELECT * FROM profil_desa WHERE id_profil = 1 LIMIT 1");
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($row) {
                        if (!empty($row['nama_desa'])) $nama_desa = $row['nama_desa'];
                        if (!empty($row['kepala_desa'])) $kepala_desa = $row['kepala_desa'];
                        // optionally read kecamatan/kabupaten from web_settings or alamat
                        $settingsPath = __DIR__ . '/web_settings.json';
                        if (file_exists($settingsPath)) {
                            $ws = json_decode(file_get_contents($settingsPath), true) ?: [];
                            $alamat_kontak = $ws['kontak']['alamat'] ?? '';
                            if (stripos($alamat_kontak, 'Kab. Sumenep') !== false || stripos($alamat_kontak, 'Sumenep') !== false) {
                                $nama_kabupaten = 'SUMENEP';
                            }
                            if (stripos($alamat_kontak, 'Kec.') !== false) {
                                // ambil kata setelah 'Kec.' sebagai kecamatan
                                if (preg_match('/Kec\.\s*([^,]+)/i', $alamat_kontak, $m)) {
                                    $nama_kecamatan = trim($m[1]);
                                }
                            }
                        }
                    }

                    // Coba cari custom template untuk tipe surat ini
                    try {
                        $stmt = $koneksi->prepare("SELECT template_html FROM template_surat WHERE tipe_surat = ?");
                        $stmt->execute([$type]);
                        $template_row = $stmt->fetch(PDO::FETCH_ASSOC);
                        if ($template_row) {
                            $custom_template = $template_row['template_html'];
                        }
                    } catch (Exception $e) {
                        // Ignore if template table doesn't exist yet
                    }

                } catch (Exception $e) {
                    // ignore DB read errors, pakai default
                }
            }
        } catch (Exception $e) {
            // ignore
        }
    }

    // Siapkan variabel untuk replacement
    $nama_usaha = htmlspecialchars($data['nama_usaha'] ?? '-');
    $lokasi_usaha = nl2br(htmlspecialchars($data['lokasi_usaha'] ?? $data['alamat'] ?? '-'));
    $keterangan = nl2br(htmlspecialchars($data['keterangan'] ?? 'Tidak mampu secara ekonomi'));
    $status_tinggal = htmlspecialchars($data['status_tinggal'] ?? 'Tetap');

    // Jika ada custom template, gunakan dan replace variabelnya
    if ($custom_template) {
        $html = $custom_template;
        $html = str_replace('{nama}', $nama, $html);
        $html = str_replace('{nik}', $nik, $html);
        $html = str_replace('{alamat}', $alamat, $html);
        $html = str_replace('{tanggal}', $tanggal_sekarang, $html);
        $html = str_replace('{nama_usaha}', $nama_usaha, $html);
        $html = str_replace('{lokasi_usaha}', $lokasi_usaha, $html);
        $html = str_replace('{keterangan}', $keterangan, $html);
        $html = str_replace('{status_tinggal}', $status_tinggal, $html);
        $html = str_replace('{kepala_desa}', $kepala_desa, $html);
        $html = str_replace('{nama_desa}', $nama_desa, $html);
        $html = str_replace('{nama_kecamatan}', $nama_kecamatan, $html);
        $html = str_replace('{nama_kabupaten}', $nama_kabupaten, $html);
        return $html;
    }

    if ($type === 'nikah') {
        $html = "<div style='font-family: serif; max-width:800px; margin:0 auto;'>
            <div style='text-align:center;'>
                <div style='font-weight:bold'>PEMERINTAH KABUPATEN {$nama_kabupaten}</div>
                <div style='font-weight:bold'>KECAMATAN {$nama_kecamatan}</div>
                <div style='font-weight:bold'>{$nama_desa}</div>
                <hr style='border:1px solid #000'>
                <h2 style='margin:8px 0 0 0'>SURAT KETERANGAN NIKAH</h2>
                <div>Nomor: ....................................</div>
            </div>

            <p>Yang bertanda tangan di bawah ini, {$kepala_desa} {$nama_desa}, menerangkan bahwa:</p>

            <table style='width:100%; margin-top:8px; border-collapse:collapse;'>
                <tr><td style='width:150px; vertical-align:top;'>Nama</td><td>: {$nama}</td></tr>
                <tr><td style='vertical-align:top;'>NIK</td><td>: {$nik}</td></tr>
                <tr><td style='vertical-align:top;'>Alamat</td><td>: {$alamat}</td></tr>
            </table>

            <p>Surat keterangan ini dibuat untuk keperluan pernikahan dan berdasarkan keterangan yang diberikan oleh yang bersangkutan.</p>

            <p>Demikian surat keterangan ini dibuat agar dapat digunakan sebagaimana mestinya.</p>

            <div style='width:100%; display:flex; justify-content:flex-end; margin-top:60px;'>
                <div style='text-align:center;'>
                    <div>................, {$tanggal_sekarang}</div>
                    <div>{$kepala_desa}</div>
                    <br><br><br>
                    <div style='text-decoration:underline'>(................................)</div>
                </div>
            </div>

        </div>";

        return $html;
    }

    if ($type === 'sku' || stripos($type, 'usaha') !== false) {
        $html = "<div style='font-family: serif; max-width:800px; margin:0 auto;'>
            <div style='text-align:center;'>
                <div style='font-weight:bold'>PEMERINTAH KABUPATEN {$nama_kabupaten}</div>
                <div style='font-weight:bold'>KECAMATAN {$nama_kecamatan}</div>
                <div style='font-weight:bold'>{$nama_desa}</div>
                <hr style='border:1px solid #000'>
                <h2 style='margin:8px 0 0 0'>SURAT KETERANGAN USAHA (SKU)</h2>
                <div>Nomor: ....................................</div>
            </div>

            <p>Yang bertanda tangan di bawah ini, {$kepala_desa} {$nama_desa}, menerangkan bahwa:</p>

            <table style='width:100%; margin-top:8px; border-collapse:collapse;'>
                <tr><td style='width:180px; vertical-align:top;'>Nama Pemilik</td><td>: {$nama}</td></tr>
                <tr><td style='vertical-align:top;'>NIK</td><td>: {$nik}</td></tr>
                <tr><td style='vertical-align:top;'>Nama Usaha</td><td>: " . htmlspecialchars($data['nama_usaha'] ?? '-') . "</td></tr>
                <tr><td style='vertical-align:top;'>Lokasi Usaha</td><td>: " . nl2br(htmlspecialchars($data['lokasi_usaha'] ?? $data['alamat'] ?? '-')) . "</td></tr>
            </table>

            <p>Surat keterangan ini diberikan untuk keperluan administrasi usaha dan tidak dapat dipergunakan sebagai dokumen legal lain.</p>

            <div style='width:100%; display:flex; justify-content:flex-end; margin-top:60px;'>
                <div style='text-align:center;'>
                    <div>................, {$tanggal_sekarang}</div>
                    <div>{$kepala_desa}</div>
                    <br><br><br>
                    <div style='text-decoration:underline'>(................................)</div>
                </div>
            </div>
        </div>";

        return $html;
    }

    if ($type === 'sktm' || stripos($type, 'tidak mampu') !== false || stripos($type, 'tidak mampu') !== false) {
        $html = "<div style='font-family: serif; max-width:800px; margin:0 auto;'>
            <div style='text-align:center;'>
                <div style='font-weight:bold'>PEMERINTAH KABUPATEN {$nama_kabupaten}</div>
                <div style='font-weight:bold'>KECAMATAN {$nama_kecamatan}</div>
                <div style='font-weight:bold'>{$nama_desa}</div>
                <hr style='border:1px solid #000'>
                <h2 style='margin:8px 0 0 0'>SURAT KETERANGAN TIDAK MAMPU (SKTM)</h2>
                <div>Nomor: ....................................</div>
            </div>

            <p>Yang bertanda tangan di bawah ini, {$kepala_desa} {$nama_desa}, menerangkan bahwa:</p>

            <table style='width:100%; margin-top:8px; border-collapse:collapse;'>
                <tr><td style='width:150px; vertical-align:top;'>Nama</td><td>: {$nama}</td></tr>
                <tr><td style='vertical-align:top;'>NIK</td><td>: {$nik}</td></tr>
                <tr><td style='vertical-align:top;'>Alamat</td><td>: {$alamat}</td></tr>
                <tr><td style='vertical-align:top;'>Keterangan</td><td>: " . nl2br(htmlspecialchars($data['keterangan'] ?? 'Tidak mampu secara ekonomi')) . "</td></tr>
            </table>

            <p>Surat keterangan ini dibuat untuk keperluan pengajuan bantuan dan/atau program sosial sesuai ketentuan yang berlaku.</p>

            <div style='width:100%; display:flex; justify-content:flex-end; margin-top:60px;'>
                <div style='text-align:center;'>
                    <div>................, {$tanggal_sekarang}</div>
                    <div>{$kepala_desa}</div>
                    <br><br><br>
                    <div style='text-decoration:underline'>(................................)</div>
                </div>
            </div>
        </div>";

        return $html;
    }

    if ($type === 'skd' || stripos($type, 'domisili') !== false) {
        $html = "<div style='font-family: serif; max-width:800px; margin:0 auto;'>
            <div style='text-align:center;'>
                <div style='font-weight:bold'>PEMERINTAH KABUPATEN {$nama_kabupaten}</div>
                <div style='font-weight:bold'>KECAMATAN {$nama_kecamatan}</div>
                <div style='font-weight:bold'>{$nama_desa}</div>
                <hr style='border:1px solid #000'>
                <h2 style='margin:8px 0 0 0'>SURAT KETERANGAN DOMISILI (SKD)</h2>
                <div>Nomor: ....................................</div>
            </div>

            <p>Yang bertanda tangan di bawah ini, {$kepala_desa} {$nama_desa}, menerangkan bahwa:</p>

            <table style='width:100%; margin-top:8px; border-collapse:collapse;'>
                <tr><td style='width:150px; vertical-align:top;'>Nama</td><td>: {$nama}</td></tr>
                <tr><td style='vertical-align:top;'>NIK</td><td>: {$nik}</td></tr>
                <tr><td style='vertical-align:top;'>Alamat</td><td>: {$alamat}</td></tr>
                <tr><td style='vertical-align:top;'>Status Tinggal</td><td>: " . htmlspecialchars($data['status_tinggal'] ?? 'Tetap') . "</td></tr>
            </table>

            <p>Surat keterangan domisili ini dibuat untuk melengkapi persyaratan administrasi yang diperlukan.</p>

            <div style='width:100%; display:flex; justify-content:flex-end; margin-top:60px;'>
                <div style='text-align:center;'>
                    <div>................, {$tanggal_sekarang}</div>
                    <div>{$kepala_desa}</div>
                    <br><br><br>
                    <div style='text-decoration:underline'>(................................)</div>
                </div>
            </div>
        </div>";

        return $html;
    }

    return "<p>Template untuk jenis surat '{$type}' belum tersedia.</p>";
}

?>
