<?php

$alert_sukses = false;
$layanan_pilihan = '';
$nomor_reg = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ajukan_layanan'])) {
    $jenis_surat = htmlspecialchars($_POST['jenis_surat']);
    $nik = htmlspecialchars($_POST['nik']);
    $nama_pemohon = htmlspecialchars($_POST['nama_pemohon']);
    $no_hp = htmlspecialchars($_POST['no_hp']);
    $keperluan = htmlspecialchars($_POST['keperluan']);
    
    
    $nomor_reg = 'REG/BLUTO/' . date('Ymd') . '/' . rand(100, 999);
    
    try {
        $stmtInsert = $koneksi->prepare("INSERT INTO pengajuan_surat (nama_pemohon, nik, no_hp, jenis_surat, keperluan, nomor_registrasi) VALUES (:nama, :nik, :no_hp, :jenis, :keperluan, :reg)");
        $stmtInsert->execute([
            ':nama' => $nama_pemohon,
            ':nik' => $nik,
            ':no_hp' => $no_hp,
            ':jenis' => $jenis_surat,
            ':keperluan' => $keperluan,
            ':reg' => $nomor_reg
        ]);
        
        $alert_sukses = true;
        $layanan_pilihan = $jenis_surat;
    } catch (PDOException $e) {
        die("Error database: " . $e->getMessage());
    }
}
?>

<div class="container mt-2 mb-5">
    
    <div class="bg-success text-white py-4 px-4 rounded-4 shadow-sm mb-4" style="background: linear-gradient(135deg, var(--emerald-primary) 0%, #115c3a 100%);">
        <h2 class="fw-bold mb-1"><i class="bi bi-file-earmark-text me-2"></i>Layanan Publik Mandiri</h2>
        <p class="mb-0 text-white-50 small">Ajukan surat-surat keterangan administrasi secara mandiri dan cepat melalui portal desa.</p>
    </div>

    
    <?php if ($alert_sukses): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm p-4 mb-4" role="alert">
            <h5 class="alert-heading fw-bold mb-1"><i class="bi bi-check-circle-fill me-2"></i>Pengajuan Berhasil Dikirim!</h5>
            <p class="mb-0 small text-muted">Pengajuan untuk <strong><?= $layanan_pilihan ?></strong> telah terdaftar di sistem. Silakan simpan nomor registrasi pengajuan Anda: <strong class="text-success"><?= $nomor_reg ?></strong>. Petugas Balai Desa Bluto akan memverifikasi berkas Anda dan menghubungi via WhatsApp.</p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-success border-bottom pb-2 mb-3">
                        <i class="bi bi-list-check me-2"></i>Jenis Layanan yang Tersedia
                    </h5>
                    
                    <div class="accordion accordion-flush" id="accordionPersyaratan">
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold text-dark small" type="button" data-bs-toggle="collapse" data-bs-target="#flush-sku">
                                    <i class="bi bi-shop me-2 text-success"></i> Surat Keterangan Usaha (SKU)
                                </button>
                            </h2>
                            <div id="flush-sku" class="accordion-collapse collapse" data-bs-parent="#accordionPersyaratan">
                                <div class="accordion-body text-muted small">
                                    <strong>Persyaratan Dokumen:</strong>
                                    <ul class="ps-3 mt-1 mb-0">
                                        <li>Fotokopi KTP Pemohon</li>
                                        <li>Fotokopi Kartu Keluarga (KK)</li>
                                        <li>Surat Pengantar RT/RW setempat</li>
                                        <li>Foto tempat usaha / jenis barang dagangan</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold text-dark small" type="button" data-bs-toggle="collapse" data-bs-target="#flush-sktm">
                                    <i class="bi bi-heart-pulse-fill me-2 text-success"></i> Surat Keterangan Tidak Mampu (SKTM)
                                </button>
                            </h2>
                            <div id="flush-sktm" class="accordion-collapse collapse" data-bs-parent="#accordionPersyaratan">
                                <div class="accordion-body text-muted small">
                                    <strong>Persyaratan Dokumen:</strong>
                                    <ul class="ps-3 mt-1 mb-0">
                                        <li>Fotokopi KTP & KK</li>
                                        <li>Surat Pengantar RT/RW (menerangkan kondisi ekonomi)</li>
                                        <li>Surat pernyataan tidak mampu bermaterai 10.000</li>
                                        <li>Foto rumah tinggal pemohon tampak depan</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold text-dark small" type="button" data-bs-toggle="collapse" data-bs-target="#flush-skd">
                                    <i class="bi bi-geo-alt-fill me-2 text-success"></i> Surat Keterangan Domisili (SKD)
                                </button>
                            </h2>
                            <div id="flush-skd" class="accordion-collapse collapse" data-bs-parent="#accordionPersyaratan">
                                <div class="accordion-body text-muted small">
                                    <strong>Persyaratan Dokumen:</strong>
                                    <ul class="ps-3 mt-1 mb-0">
                                        <li>Fotokopi KTP asli</li>
                                        <li>Fotokopi KK</li>
                                        <li>Surat Pengantar RT/RW domisili setempat</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            
            <div class="card border-0 shadow-sm rounded-4 bg-success bg-opacity-5">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-success mb-3"><i class="bi bi-info-circle-fill me-2"></i>Alur Pelayanan Mandiri</h5>
                    <ol class="small text-muted ps-3 mb-0">
                        <li class="mb-2">Isi formulir pengajuan di sebelah kanan dengan lengkap dan benar.</li>
                        <li class="mb-2">Sistem akan memproses draf administrasi dan memberikan Kode Registrasi Pengajuan.</li>
                        <li class="mb-2">Petugas pelayanan balai desa akan melakukan verifikasi data dalam 1x24 jam kerja.</li>
                        <li>Ambil cetak fisik surat di loket pelayanan Balai Desa Bluto dengan membawa persyaratan asli.</li>
                    </ol>
                </div>
            </div>
        </div>

        
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-success border-bottom pb-2 mb-4">
                        <i class="bi bi-pencil-square me-2"></i>Formulir Pengajuan Surat
                    </h5>
                    
                    <form action="index.php?page=layanan" method="POST">
                        <input type="hidden" name="ajukan_layanan" value="1">
                        
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Pilih Jenis Surat Keterangan</label>
                            <select name="jenis_surat" class="form-select rounded-3" required>
                                <option value="" disabled selected>-- Pilih Surat Keterangan --</option>
                                <option value="Surat Keterangan Usaha (SKU)">Surat Keterangan Usaha (SKU)</option>
                                <option value="Surat Keterangan Tidak Mampu (SKTM)">Surat Keterangan Tidak Mampu (SKTM)</option>
                                <option value="Surat Keterangan Domisili (SKD)">Surat Keterangan Domisili (SKD)</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Nomor Induk Kependudukan (NIK)</label>
                            <input type="text" name="nik" class="form-control rounded-3" placeholder="Masukkan 16 digit NIK Anda" pattern="\d{16}" title="Harus 16 digit angka" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Nama Lengkap (Sesuai KTP)</label>
                            <input type="text" name="nama_pemohon" class="form-control rounded-3" placeholder="Masukkan nama lengkap" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Nomor HP / WhatsApp (Aktif)</label>
                            <input type="text" name="no_hp" class="form-control rounded-3" placeholder="Contoh: 081234567890" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-semibold">Alamat Lengkap & Keperluan / Keterangan Tambahan</label>
                            <textarea name="keperluan" class="form-control rounded-3" rows="4" placeholder="Contoh: Dusun RT 01/RW 02. Syarat pengajuan modal usaha KUR Bank Mandiri" required></textarea>
                        </div>

                        <button type="submit" class="btn btn-success w-100 py-2.5 rounded-pill fw-bold">
                            <i class="bi bi-file-earmark-plus-fill me-2"></i> Ajukan Permohonan Surat
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
