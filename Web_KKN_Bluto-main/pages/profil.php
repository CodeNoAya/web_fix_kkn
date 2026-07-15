<?php

try {
    $stmt = $koneksi->query("SELECT * FROM profil_desa WHERE id_profil = 1");
    $profil = $stmt->fetch(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $profil = [];
}

$nama_desa = !empty($profil['nama_desa']) ? $profil['nama_desa'] : 'Desa Bluto';
$kepala_desa = !empty($profil['kepala_desa']) ? $profil['kepala_desa'] : 'Bapak H. Akhmad';
$visi = !empty($profil['visi']) ? $profil['visi'] : 'Visi “Menuju Bluto Sebagai Desa Andalan (Aman, Damai, Aktif, Loyal dan Nyaman)”';
$misi = !empty($profil['misi']) ? $profil['misi'] : "Meningkatkan kualitas tata kelola pemerintahan desa yang bersih, transparan, dan berorientasi pada pelayanan masyarakat secara digital.
Mengoptimalkan potensi pertanian, kelautan, dan industri kreatif melalui pemberdayaan produk lokal (UMKM Dapur Lokal).
Membangun sarana dan prasarana infrastruktur desa yang merata guna mempercepat akses perekonomian warga.
Meningkatkan kerja sama pemuda, mahasiswa (KKN), tokoh masyarakat, dan pemerintah desa untuk mewujudkan inovasi desa.";
$foto_kades = !empty($profil['foto_kades']) ? 'assets/img/'.$profil['foto_kades'] : 'assets/img/stickman-placeholder.svg';
$masa_jabatan = !empty($profil['masa_jabatan']) ? $profil['masa_jabatan'] : '2021 - 2027';

try {
    $stmtStruktur = $koneksi->query("SELECT * FROM struktur_pemerintahan ORDER BY urutan ASC, id_struktur ASC");
    $strukturList = $stmtStruktur->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $strukturList = [];
}
?>

<div class="container mt-2">
    
    <div class="bg-success text-white py-4 px-4 rounded-4 shadow-sm mb-4" style="background: linear-gradient(135deg, var(--emerald-primary) 0%, #115c3a 100%);">
        <h2 class="fw-bold mb-1"><i class="bi bi-info-circle me-2"></i>Profil Desa Bluto</h2>
        <p class="mb-0 text-white-50 small">Kenal lebih dekat dengan sejarah, visi, misi, dan aparatur pemerintahan Desa Bluto.</p>
    </div>

    <div class="row g-4">
        
        <div class="col-lg-8">
            
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h4 class="fw-bold text-success border-bottom pb-2 mb-3"><i class="bi bi-compass me-2"></i>Visi & Misi</h4>
                    
                    <div class="mb-4">
                        <h5 class="fw-bold text-dark mb-2">Visi</h5>
                        <blockquote class="blockquote bg-light p-3 rounded border-start border-success border-4 fs-6">
                            "<?= htmlspecialchars($visi) ?>"
                        </blockquote>
                    </div>
                    
                    <div>
                        <h5 class="fw-bold text-dark mb-2">Misi</h5>
                        <?php $misiItems = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $misi)), static function ($line): bool { return $line !== ''; })); ?>
                        <?php if (!empty($misiItems)): ?>
                            <ol class="text-muted small ps-3">
                                <?php foreach ($misiItems as $item): ?>
                                    <li class="mb-2"><?= htmlspecialchars($item) ?></li>
                                <?php endforeach; ?>
                            </ol>
                        <?php else: ?>
                            <div class="text-muted small">Belum ada misi yang ditambahkan.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h4 class="fw-bold text-success border-bottom pb-2 mb-3"><i class="bi bi-hourglass-split me-2"></i>Sejarah Desa Bluto</h4>
                    <p class="text-muted small">
                        Desa Bluto bermula dari cerita masyarakat, dimana wilayah ini mulanya adalah daerah berbatu dengan kontur yang beragam mulai dari kecil hingga besar. Pada beberapa tempat, bahkan terdapat gua yang terbentuk dari endapan batu yang diperkirakan sudah berusia hingga ratusan tahun. Pemukim awal yang menempati wilayah ini kemudian menyebut tempat ini dengan sebutan Abulu Betoh yang dalam Bahasa Madura bermakna berbulu / terbungkus batu yang menunjukkan bahwa wilayah dipenuhi dengan batu yang sangat banyak. Seiring berjalannya waktu istilah tersebut kemudian disederhanakan menjadi “Bluto” agar mudah diucapkan dan menjadi nama desa hingga saat ini.
                    </p>
                </div>
            </div>
        </div>

        
        <div class="col-lg-4">
            
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden text-center">
                <div class="bg-success py-3 text-white fw-bold">
                    Kepala Desa Bluto
                </div>
                <div class="card-body p-4">
                    <img src="<?= $foto_kades ?>" class="rounded-circle img-thumbnail mb-3 object-fit-cover shadow-sm" alt="Kepala Desa" style="width: 140px; height: 140px;">
                    <h5 class="fw-bold mb-1 text-dark"><?= htmlspecialchars($kepala_desa) ?></h5>
                    <p class="text-muted small mb-3">Masa Jabatan: <?= htmlspecialchars($masa_jabatan) ?></p>
                    <div class="bg-light py-2 px-3 rounded small text-start">
                        <div class="mb-1"><strong>Alamat Balai:</strong> Jl. Raya Bluto, Kec. Bluto, Kab. Sumenep</div>
                        <div><strong>Email:</strong> pemdes@bluto.desa.id</div>
                    </div>
                </div>
            </div>

            
            <div class="card border-0 shadow-sm rounded-4" id="aparatur">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-success border-bottom pb-2 mb-3"><i class="bi bi-people me-2"></i>Struktur Pemerintahan</h5>
                    
                    <?php if (!empty($strukturList)): ?>
                        <?php foreach ($strukturList as $item): ?>
                            <?php $fotoStruktur = !empty($item['foto']) ? 'assets/img/' . $item['foto'] : 'assets/img/stickman-placeholder.svg'; ?>
                            <div class="d-flex align-items-center mb-3">
                                <img src="<?= htmlspecialchars($fotoStruktur) ?>" class="rounded-circle object-fit-cover me-3 border" style="width: 45px; height: 45px;" alt="<?= htmlspecialchars($item['nama']) ?>">
                                <div>
                                    <h6 class="mb-0 fw-bold small text-dark"><?= htmlspecialchars($item['nama']) ?></h6>
                                    <small class="text-muted" style="font-size: 11px;"><?= htmlspecialchars($item['jabatan']) ?></small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-muted small">Belum ada data struktur pemerintahan.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
