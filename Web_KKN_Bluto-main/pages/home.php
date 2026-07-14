<?php

$settings_file = 'config/web_settings.json';
if (file_exists($settings_file)) {
    $settings = json_decode(file_get_contents($settings_file), true);
} else {
    $settings = [
        'statistik' => ['total_penduduk' => 4321, 'laki_laki' => 2204, 'perempuan' => 2117],
        'apbdes' => ['target_pendapatan' => '1,25 Miliar', 'realisasi_pendapatan' => 92, 'target_belanja' => '1,18 Miliar', 'realisasi_belanja' => 88]
    ];
}

try {
    $queryBerita = "SELECT berita.*, kategori_berita.nama_kategori 
                    FROM berita 
                    JOIN kategori_berita ON berita.id_kategori = kategori_berita.id_kategori 
                    ORDER BY tanggal_publikasi DESC LIMIT 5";
    $stmtBerita = $koneksi->prepare($queryBerita);
    $stmtBerita->execute();
    $dataBerita = $stmtBerita->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $dataBerita = []; 
}

try {
    $queryProfil = "SELECT * FROM profil_desa WHERE id_profil = 1";
    $stmtProfil = $koneksi->prepare($queryProfil);
    $stmtProfil->execute();
    $profil = $stmtProfil->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $profil = [
        'nama_desa' => 'Desa Bluto',
        'kepala_desa' => 'Bapak Kepala Desa',
        'visi' => 'Mewujudkan Desa Bluto yang Maju dan Sejahtera.'
    ];
}

try {
    $koneksi->exec("CREATE TABLE IF NOT EXISTS struktur_pemerintahan (
        id_struktur INTEGER PRIMARY KEY AUTOINCREMENT,
        nama TEXT,
        jabatan TEXT,
        foto TEXT,
        urutan INTEGER DEFAULT 0
    )");
} catch (PDOException $e) {
    // Abaikan jika tabel sudah ada
}

try {
    $stmtStruktur = $koneksi->query("SELECT * FROM struktur_pemerintahan ORDER BY urutan ASC, id_struktur ASC");
    $strukturList = $stmtStruktur->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $strukturList = [];
}
?>

<div id="heroCarousel" class="carousel slide shadow-sm rounded-4 overflow-hidden mb-4" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
    </div>
    <div class="carousel-inner">
        
        <div class="carousel-item active" style="height: 380px;">
            <div class="w-100 h-100" style="background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('assets/img/balaidesa.jpeg') no-repeat center bottom; background-size: cover;"></div>
            <div class="carousel-caption d-flex flex-column justify-content-center h-100 text-start px-md-5">
                <span class="badge bg-success self-align-start mb-2 px-3 py-2 rounded-pill uppercase fw-bold" style="max-width: max-content;">Portal Resmi</span>
                <h1 class="display-6 fw-bold text-white mb-2">Selamat Datang di <?= htmlspecialchars($profil['nama_desa']) ?></h1>
                <p class="lead text-white-50 d-none d-md-block fs-6"><?= htmlspecialchars($profil['visi']) ?></p>
                <div>
                    <a href="index.php?page=profil" class="btn btn-success rounded-pill px-4 mt-2">Selengkapnya</a>
                </div>
            </div>
        </div>
        
        <div class="carousel-item" style="height: 380px;">
            <div class="w-100 h-100" style="background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1517048676732-d65bc937f952?auto=format&fit=crop&q=80&w=1200&h=500') no-repeat center center; background-size: cover;"></div>
            <div class="carousel-caption d-flex flex-column justify-content-center h-100 text-start px-md-5">
                <span class="badge bg-primary self-align-start mb-2 px-3 py-2 rounded-pill uppercase fw-bold" style="max-width: max-content;">Kegiatan KKN</span>
                <h1 class="display-6 fw-bold text-white mb-2">Pengabdian Masyarakat KKN UTM 42</h1>
                <p class="lead text-white-50 d-none d-md-block fs-6">Sinergi antara mahasiswa dan warga desa untuk pembangunan berkelanjutan, pemberdayaan UMKM, dan optimalisasi layanan digital desa.</p>
                <div>
                    <a href="index.php?page=berita" class="btn btn-primary rounded-pill px-4 mt-2">Lihat Kegiatan</a>
                </div>
            </div>
        </div>
        
        <div class="carousel-item" style="height: 380px;">
            <div class="w-100 h-100" style="background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1605000797499-95a51c5269ae?auto=format&fit=crop&q=80&w=1200&h=500') no-repeat center center; background-size: cover;"></div>
            <div class="carousel-caption d-flex flex-column justify-content-center h-100 text-start px-md-5">
                <span class="badge bg-warning text-dark self-align-start mb-2 px-3 py-2 rounded-pill uppercase fw-bold" style="max-width: max-content;">Dapur Lokal</span>
                <h1 class="display-6 fw-bold text-white mb-2">Dukung Ekonomi Kreatif UMKM Desa</h1>
                <p class="lead text-white-50 d-none d-md-block fs-6">Katalog digital produk-produk kerajinan, kuliner asli, dan hasil bumi petani unggulan Desa Bluto langsung ke pembeli.</p>
                <div>
                    <a href="index.php?page=dapur-lokal" class="btn btn-warning text-dark rounded-pill px-4 mt-2">Beli Produk Lokal</a>
                </div>
            </div>
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

<div class="bg-success text-white py-2 px-3 rounded-3 shadow-sm mb-4 d-flex align-items-center">
    <span class="badge bg-dark me-3 uppercase fw-bold px-3 py-2"><i class="bi bi-megaphone-fill me-1"></i> Pengumuman</span>
    <marquee class="small mb-0" scrollamount="4">
        Selamat Datang di Portal Resmi Desa Bluto, Kecamatan Bluto, Kabupaten Sumenep. Mari wujudkan desa yang maju, transparan, dan inovatif. Kunjungi Dapur Lokal Bluto untuk membeli produk-produk UMKM warga desa kami!
    </marquee>
</div>

<div class="row">
    
    <div class="col-lg-8 mb-4">
        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
            <h4 class="fw-bold mb-0 text-success"><i class="bi bi-newspaper me-2"></i>Berita & Kegiatan Terbaru</h4>
            <a href="index.php?page=berita" class="btn btn-outline-success btn-sm rounded-pill">Lihat Semua</a>
        </div>
        
        <?php if (count($dataBerita) > 0): ?>
            <?php foreach ($dataBerita as $berita): ?>
            
            <div class="card border-0 shadow-sm rounded-3 mb-3 overflow-hidden position-relative hover-lift" style="transition: transform 0.2s ease, box-shadow 0.2s ease;">
                <div class="row g-0">
                    <div class="col-md-4">
                        <?php 
                            $gambar = !empty($berita['gambar_cover']) ? 'assets/img/'.$berita['gambar_cover'] : 'https://images.unsplash.com/photo-1517048676732-d65bc937f952?auto=format&fit=crop&q=60';
                        ?>
                        <img src="<?= $gambar ?>" class="w-100 h-100 object-fit-cover rounded-start" alt="<?= htmlspecialchars($berita['judul']) ?>" style="min-height: 180px;">
                    </div>
                    <div class="col-md-8 d-flex flex-column">
                        <div class="card-body p-4 d-flex flex-column h-100">
                            <div class="mb-2">
                                <span class="badge bg-success bg-opacity-10 text-success fw-semibold px-2 py-1 rounded"><?= htmlspecialchars($berita['nama_kategori']) ?></span>
                                <small class="text-muted ms-2"><i class="bi bi-calendar3 me-1"></i><?= date('d M Y', strtotime($berita['tanggal_publikasi'])) ?></small>
                            </div>
                            <h5 class="card-title fw-bold text-dark mb-2"><?= htmlspecialchars($berita['judul']) ?></h5>
                            <p class="card-text text-muted small flex-grow-1">
                                <?= substr(strip_tags($berita['isi']), 0, 120) ?>...
                            </p>
                            <a href="index.php?page=detail_berita&slug=<?= $berita['slug'] ?>" class="btn btn-outline-success btn-sm rounded-pill align-self-start mt-2">Baca Selengkapnya <i class="bi bi-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-info py-4 text-center rounded-3">
                <i class="bi bi-info-circle fs-2 d-block mb-2 text-info"></i>
                Belum ada berita yang dipublikasikan.
            </div>
        <?php endif; ?>
    </div>

    
    <div class="col-lg-4">
        
        
        <div class="widget-card">
            <div class="widget-header">
                <i class="bi bi-clock-fill"></i> Jadwal Shalat Bluto
            </div>
            <div class="widget-body p-0">
                <div class="bg-success bg-opacity-10 text-success text-center py-2 px-3 small border-bottom">
                    <i class="bi bi-geo-alt-fill me-1"></i> Sumenep & Sekitarnya
                </div>
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center py-2 px-3">
                        <span class="small fw-semibold"><i class="bi bi-moon me-2 text-success"></i>Imsak</span>
                        <span class="badge bg-dark rounded-pill fw-bold" id="prayer-imsak">--:--</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center py-2 px-3 bg-success bg-opacity-5">
                        <span class="small fw-semibold"><i class="bi bi-sunrise me-2 text-success"></i>Subuh</span>
                        <span class="badge bg-success rounded-pill fw-bold" id="prayer-subuh">--:--</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center py-2 px-3">
                        <span class="small fw-semibold"><i class="bi bi-sun me-2 text-success"></i>Dzuhur</span>
                        <span class="badge bg-dark rounded-pill fw-bold" id="prayer-dzuhur">--:--</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center py-2 px-3">
                        <span class="small fw-semibold"><i class="bi bi-sun-fill me-2 text-success"></i>Ashar</span>
                        <span class="badge bg-dark rounded-pill fw-bold" id="prayer-ashar">--:--</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center py-2 px-3 bg-success bg-opacity-5">
                        <span class="small fw-semibold"><i class="bi bi-sunset me-2 text-success"></i>Maghrib</span>
                        <span class="badge bg-success rounded-pill fw-bold" id="prayer-maghrib">--:--</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center py-2 px-3">
                        <span class="small fw-semibold"><i class="bi bi-stars me-2 text-success"></i>Isya</span>
                        <span class="badge bg-dark rounded-pill fw-bold" id="prayer-isya">--:--</span>
                    </div>
                </div>
            </div>
        </div>

        
        <?php
        $stat_total = (int)$settings['statistik']['total_penduduk'];
        $stat_l = (int)$settings['statistik']['laki_laki'];
        $stat_p = (int)$settings['statistik']['perempuan'];
        $pct_l = $stat_total > 0 ? round(($stat_l / $stat_total) * 100) : 0;
        $pct_p = $stat_total > 0 ? round(($stat_p / $stat_total) * 100) : 0;
        ?>
        <div class="widget-card" id="statistik">
            <div class="widget-header">
                <i class="bi bi-bar-chart-line-fill"></i> Statistik Demografi
            </div>
            <div class="widget-body">
                <div class="text-center mb-3">
                    <h6 class="text-muted small mb-1">TOTAL POPULASI</h6>
                    <h3 class="fw-bold text-success mb-0"><?= number_format($stat_total, 0, ',', '.') ?> Warga</h3>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="fw-medium"><i class="bi bi-gender-male text-primary me-1"></i>Laki-laki</span>
                        <span class="text-muted fw-bold"><?= number_format($stat_l, 0, ',', '.') ?> Jiwa (<?= $pct_l ?>%)</span>
                    </div>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: <?= $pct_l ?>%"></div>
                    </div>
                </div>
                
                <div>
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="fw-medium"><i class="bi bi-gender-female text-danger me-1"></i>Perempuan</span>
                        <span class="text-muted fw-bold"><?= number_format($stat_p, 0, ',', '.') ?> Jiwa (<?= $pct_p ?>%)</span>
                    </div>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: <?= $pct_p ?>%"></div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="widget-card">
            <div class="widget-header">
                <i class="bi bi-people-fill"></i> Aparatur Desa & Struktur Pemerintahan
            </div>
            <div class="widget-body px-0 py-2">
                <?php if (!empty($strukturList)): ?>
                    <?php foreach ($strukturList as $index => $item): ?>
                        <?php $fotoStruktur = !empty($item['foto']) ? 'assets/img/' . $item['foto'] : 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&q=80&w=100&h=100'; ?>
                        <div class="d-flex align-items-center px-3 py-2 <?= ($index < count($strukturList) - 1) ? 'border-bottom' : '' ?>">
                            <img src="<?= htmlspecialchars($fotoStruktur) ?>" class="rounded-circle object-fit-cover me-3 border" style="width: 40px; height: 40px;" alt="<?= htmlspecialchars($item['nama']) ?>">
                            <div>
                                <h6 class="mb-0 fw-bold small text-dark"><?= htmlspecialchars($item['nama']) ?></h6>
                                <small class="text-muted" style="font-size: 11px;"><?= htmlspecialchars($item['jabatan']) ?></small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="px-3 py-3 text-muted small">Belum ada data struktur pemerintahan.</div>
                <?php endif; ?>
            </div>
        </div>

        

        
        <div class="widget-card">
            <div class="widget-header">
                <i class="bi bi-map-fill"></i> Peta Lokasi Desa
            </div>
            <div class="widget-body p-0">
                <div id="map-home" style="height: 200px; width: 100%; z-index: 1;"></div>
            </div>
        </div>

    </div>
</div>
