<?php

$settings_file = 'config/web_settings.json';
if (file_exists($settings_file)) {
    $settings = json_decode(file_get_contents($settings_file), true);
} else {
    $settings = [
        'kontak' => [
            'alamat' => 'Jl. Raya Bluto, Kecamatan Bluto, Kabupaten Sumenep, Jawa Timur, Kode Pos 69466.',
            'email' => 'pemdes@bluto.desa.id',
            'telepon' => '0812-3456-7890 (Kantor Desa)'
        ]
    ];
}

$alert_sukses = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['kirim_pesan'])) {
    $nama_lengkap = htmlspecialchars($_POST['nama_lengkap']);
    $kontak = htmlspecialchars($_POST['kontak']);
    $isi_pesan = htmlspecialchars($_POST['isi_pesan']);

    try {
        $stmtAspirasi = $koneksi->prepare("INSERT INTO aspirasi (nama_lengkap, kontak, isi_pesan) VALUES (:nama, :kontak, :pesan)");
        $stmtAspirasi->execute([
            ':nama' => $nama_lengkap,
            ':kontak' => $kontak,
            ':pesan' => $isi_pesan
        ]);
        $alert_sukses = true;
    } catch (PDOException $e) {
        die("Error database: " . $e->getMessage());
    }
}
?>

<div class="container mt-2 mb-5">
    
    <div class="bg-success text-white py-4 px-4 rounded-4 shadow-sm mb-4" style="background: linear-gradient(135deg, var(--emerald-primary) 0%, #115c3a 100%);">
        <h2 class="fw-bold mb-1"><i class="bi bi-telephone-fill me-2"></i>Kontak & Hubungi Kami</h2>
        <p class="mb-0 text-white-50 small">Hubungi kami untuk pertanyaan administrasi, kemitraan, atau saran pembangunan Desa Bluto.</p>
    </div>

    
    <?php if ($alert_sukses): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm p-4 mb-4" role="alert">
            <h5 class="alert-heading fw-bold mb-1"><i class="bi bi-check-circle-fill me-2"></i>Pesan Berhasil Terkirim!</h5>
            <p class="mb-0 small text-muted">Terima kasih atas masukan atau pengajuan Anda. Tim Administrasi Desa Bluto atau perwakilan KKN UTM akan segera menghubungi Anda kembali.</p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        
        <div class="col-lg-7" id="hubungi">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-md-5">
                    <h4 class="fw-bold text-success border-bottom pb-2 mb-4">
                        <i class="bi bi-envelope-paper-fill me-2"></i>Kirim Pesan Aspirasi
                    </h4>
                    
                    <form action="index.php?page=kontak" method="POST">
                        <input type="hidden" name="kirim_pesan" value="1">
                        
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" class="form-control rounded-3" placeholder="Masukkan nama Anda" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Alamat Email / WhatsApp</label>
                                <input type="text" name="kontak" class="form-control rounded-3" placeholder="Contoh: 0812345xxxxx" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Subjek Pesan</label>
                            <select name="subjek" class="form-select rounded-3" required>
                                <option value="" disabled selected>-- Pilih Subjek --</option>
                                <option value="layanan">Layanan Administrasi</option>
                                <option value="umkm">Pertanyaan UMKM / Dapur Lokal</option>
                                <option value="kkn">Saran Kegiatan KKN UTM</option>
                                <option value="pengaduan">Pengaduan Masyarakat</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-semibold">Isi Pesan Anda</label>
                            <textarea name="isi_pesan" class="form-control rounded-3" rows="6" placeholder="Ketik pesan, saran, atau pertanyaan Anda secara lengkap..." required></textarea>
                        </div>

                        <button type="submit" class="btn btn-success w-100 py-2.5 rounded-pill fw-bold">
                            <i class="bi bi-send-fill me-2"></i> Kirim Pesan Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>

        
        <div class="col-lg-5">
            
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-success border-bottom pb-2 mb-3">
                        <i class="bi bi-building-fill me-2"></i>Kantor Balai Desa
                    </h5>
                    
                    <div class="d-flex align-items-start mb-3">
                        <i class="bi bi-geo-alt-fill text-success fs-5 me-3 mt-1"></i>
                        <div>
                            <h6 class="fw-bold mb-1 text-dark">Alamat Resmi</h6>
                            <p class="text-muted small mb-0"><?= htmlspecialchars($settings['kontak']['alamat']) ?></p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start mb-3">
                        <i class="bi bi-envelope-fill text-success fs-5 me-3 mt-1"></i>
                        <div>
                            <h6 class="fw-bold mb-1 text-dark">Alamat Surat Elektronik</h6>
                            <p class="text-muted small mb-0"><?= htmlspecialchars($settings['kontak']['email']) ?></p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start">
                        <i class="bi bi-telephone-fill text-success fs-5 me-3 mt-1"></i>
                        <div>
                            <h6 class="fw-bold mb-1 text-dark">Nomor Kontak & WA</h6>
                            <p class="text-muted small mb-0"><?= htmlspecialchars($settings['kontak']['telepon']) ?></p>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-success border-bottom pb-2 mb-3">
                        <i class="bi bi-clock-history me-2"></i>Jam Pelayanan
                    </h5>
                    <div class="d-flex justify-content-between small border-bottom py-2">
                        <span class="fw-semibold text-dark">Senin - Kamis</span>
                        <span class="text-muted">08:00 - 14:00 WIB</span>
                    </div>
                    <div class="d-flex justify-content-between small border-bottom py-2">
                        <span class="fw-semibold text-dark">Jumat</span>
                        <span class="text-muted">08:00 - 11:30 WIB</span>
                    </div>
                    <div class="d-flex justify-content-between small py-2">
                        <span class="fw-semibold text-danger">Sabtu - Minggu</span>
                        <span class="text-danger fw-bold">TUTUP</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-dark text-white fw-bold py-3" style="background: linear-gradient(135deg, var(--navy-dark) 0%, var(--navy-medium) 100%);">
                    <i class="bi bi-map-fill me-2 text-warning"></i>Peta Lokasi Kantor Desa Bluto
                </div>
                <div class="card-body p-0">
                    <div id="map-kontak" style="height: 400px; width: 100%; z-index: 1;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
