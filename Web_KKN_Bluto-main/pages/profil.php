<?php

try {
    $stmt = $koneksi->query("SELECT * FROM profil_desa WHERE id_profil = 1");
    $profil = $stmt->fetch(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $profil = [];
}

$nama_desa = !empty($profil['nama_desa']) ? $profil['nama_desa'] : 'Desa Bluto';
$kepala_desa = !empty($profil['kepala_desa']) ? $profil['kepala_desa'] : 'Bapak H. Akhmad';
$visi = !empty($profil['visi']) ? $profil['visi'] : 'Mewujudkan Desa Bluto yang Maju, Sejahtera, dan Inovatif berbasis Potensi Lokal.';
$foto_kades = !empty($profil['foto_kades']) ? 'assets/img/'.$profil['foto_kades'] : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&q=80&w=300&h=300';

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
                        <ol class="text-muted small ps-3">
                            <li class="mb-2">Meningkatkan kualitas tata kelola pemerintahan desa yang bersih, transparan, dan berorientasi pada pelayanan masyarakat secara digital.</li>
                            <li class="mb-2">Mengoptimalkan potensi pertanian, kelautan, dan industri kreatif melalui pemberdayaan produk lokal (UMKM Dapur Lokal).</li>
                            <li class="mb-2">Membangun sarana dan prasarana infrastruktur desa yang merata guna mempercepat akses perekonomian warga.</li>
                            <li class="mb-2">Meningkatkan kerja sama pemuda, mahasiswa (KKN), tokoh masyarakat, dan pemerintah desa untuk mewujudkan inovasi desa.</li>
                        </ol>
                    </div>
                </div>
            </div>

            
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h4 class="fw-bold text-success border-bottom pb-2 mb-3"><i class="bi bi-hourglass-split me-2"></i>Sejarah Desa Bluto</h4>
                    <p class="text-muted small">
                        Desa Bluto merupakan salah satu desa yang terletak di Kecamatan Bluto, Kabupaten Sumenep, Madura, Jawa Timur. Nama "Bluto" dipercaya oleh masyarakat setempat berasal dari perpaduan kata sejarah lisan mengenai keberadaan sumber mata air purba yang menyuburkan kawasan pertanian dan perkebunan di sekeliling wilayah ini.
                    </p>
                    <p class="text-muted small">
                        Sejak zaman kolonial hingga era kemerdekaan, Desa Bluto terus berkembang menjadi pusat perdagangan mikro di wilayah pesisir Sumenep selatan. Kehidupan masyarakat yang religius dan kental dengan gotong royong membuat berbagai program kemasyarakatan berjalan harmonis.
                    </p>
                    <p class="text-muted small">
                        Hari ini, dengan hadirnya teknologi informasi dan kerja sama bersama mahasiswa KKN dari Universitas Trunojoyo Madura (UTM), Desa Bluto bertransformasi mengadopsi keterbukaan informasi dan digitalisasi pemasaran produk lokal guna bersaing di era modern.
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
                    <p class="text-muted small mb-3">Masa Jabatan: 2021 - 2027</p>
                    <div class="bg-light py-2 px-3 rounded small text-start">
                        <div class="mb-1"><strong>Alamat Balai:</strong> Jl. Raya Bluto, Kec. Bluto, Kab. Sumenep</div>
                        <div class="mb-1"><strong>Email:</strong> pemdes@bluto.desa.id</div>
                        <div><strong>Telepon:</strong> 081234567890</div>
                    </div>
                </div>
            </div>

            
            <div class="card border-0 shadow-sm rounded-4" id="aparatur">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-success border-bottom pb-2 mb-3"><i class="bi bi-people me-2"></i>Struktur Pemerintahan & KKN</h5>
                    
                    <?php if (!empty($strukturList)): ?>
                        <?php foreach ($strukturList as $item): ?>
                            <?php $fotoStruktur = !empty($item['foto']) ? 'assets/img/' . $item['foto'] : 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&q=80&w=100&h=100'; ?>
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
