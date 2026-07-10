<?php

try {
    $stmtKategori = $koneksi->query("SELECT * FROM kategori_berita");
    $listKategori = $stmtKategori->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $listKategori = [];
}

$search = isset($_GET['cari']) ? trim($_GET['cari']) : '';
$kat_filter = isset($_GET['kategori']) ? (int)$_GET['kategori'] : 0;

$sql = "SELECT berita.*, kategori_berita.nama_kategori 
        FROM berita 
        JOIN kategori_berita ON berita.id_kategori = kategori_berita.id_kategori";

$conditions = [];
$params = [];

if ($search !== '') {
    $conditions[] = "(berita.judul LIKE :search OR berita.isi LIKE :search)";
    $params[':search'] = '%' . $search . '%';
}

if ($kat_filter > 0) {
    $conditions[] = "berita.id_kategori = :kategori";
    $params[':kategori'] = $kat_filter;
}

if (count($conditions) > 0) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$sql .= " ORDER BY tanggal_publikasi DESC";

try {
    $stmtBerita = $koneksi->prepare($sql);
    $stmtBerita->execute($params);
    $allBerita = $stmtBerita->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $allBerita = [];
}
?>

<div class="container mt-2">
    
    <div class="bg-success text-white py-4 px-4 rounded-4 shadow-sm mb-4" style="background: linear-gradient(135deg, var(--emerald-primary) 0%, #115c3a 100%);">
        <h2 class="fw-bold mb-1"><i class="bi bi-newspaper me-2"></i>Berita & Kegiatan Desa</h2>
        <p class="mb-0 text-white-50 small">Ikuti perkembangan terbaru mengenai pengumuman resmi dan kegiatan KKN di Desa Bluto.</p>
    </div>

    
    <div class="row mb-4 g-3 align-items-center">
        
        <div class="col-md-7">
            <div class="d-flex flex-wrap gap-2">
                <a href="index.php?page=berita" class="btn btn-sm rounded-pill px-3 py-2 <?= ($kat_filter === 0) ? 'btn-success' : 'btn-outline-secondary bg-white text-dark'; ?>">
                    Semua
                </a>
                <?php foreach ($listKategori as $kat): ?>
                    <a href="index.php?page=berita&kategori=<?= $kat['id_kategori'] ?>&cari=<?= urlencode($search) ?>" 
                       class="btn btn-sm rounded-pill px-3 py-2 <?= ($kat_filter === (int)$kat['id_kategori']) ? 'btn-success' : 'btn-outline-secondary bg-white text-dark'; ?>">
                        <?= htmlspecialchars($kat['nama_kategori']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        
        
        <div class="col-md-5">
            <form action="index.php" method="GET" class="d-flex">
                <input type="hidden" name="page" value="berita">
                <input type="hidden" name="kategori" value="<?= $kat_filter ?>">
                <div class="input-group input-group-sm">
                    <input type="text" name="cari" class="form-control form-control-sm rounded-start-pill px-3 py-2" placeholder="Cari berita..." value="<?= htmlspecialchars($search) ?>">
                    <button class="btn btn-success rounded-end-pill px-3" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    
    <?php if ($search !== ''): ?>
        <div class="alert alert-light border border-success bg-white py-2 px-3 small rounded-3 mb-4">
            <i class="bi bi-info-circle text-success me-2"></i>Menampilkan hasil pencarian untuk kata kunci: <strong>"<?= htmlspecialchars($search) ?>"</strong>
            <a href="index.php?page=berita&kategori=<?= $kat_filter ?>" class="text-danger text-decoration-none ms-2 small fw-bold">Reset Pencarian</a>
        </div>
    <?php endif; ?>

    
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-5">
        <?php if (count($allBerita) > 0): ?>
            <?php foreach ($allBerita as $berita): ?>
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden position-relative hover-lift" style="transition: transform 0.2s ease, box-shadow 0.2s ease;">
                        
                        <span class="badge bg-success position-absolute top-0 start-0 m-3 shadow-sm px-2 py-1.5 fw-semibold" style="z-index: 2;">
                            <?= htmlspecialchars($berita['nama_kategori']) ?>
                        </span>
                        
                        
                        <?php 
                            $gambar = !empty($berita['gambar_cover']) ? 'assets/img/'.$berita['gambar_cover'] : 'https://images.unsplash.com/photo-1517048676732-d65bc937f952?auto=format&fit=crop&q=60';
                        ?>
                        <img src="<?= $gambar ?>" class="card-img-top object-fit-cover" alt="<?= htmlspecialchars($berita['judul']) ?>" style="height: 200px;">
                        
                        <div class="card-body p-4 d-flex flex-column">
                            
                            <div class="text-muted small mb-2">
                                <i class="bi bi-calendar3 me-1"></i>
                                <?= date('d M Y', strtotime($berita['tanggal_publikasi'])) ?>
                            </div>
                            
                            
                            <h5 class="card-title fw-bold text-dark mb-2 lh-base" style="font-size: 16px; min-height: 48px;">
                                <?= htmlspecialchars($berita['judul']) ?>
                            </h5>
                            
                            
                            <p class="card-text text-muted small flex-grow-1">
                                <?= substr(strip_tags($berita['isi']), 0, 95) ?>...
                            </p>
                            
                            
                            <a href="index.php?page=detail_berita&slug=<?= $berita['slug'] ?>" class="btn btn-outline-success btn-sm rounded-pill mt-3 align-self-start px-3">
                                Baca Detail <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <div class="alert alert-info py-4 rounded-4 bg-white border">
                    <i class="bi bi-emoji-frown fs-1 d-block mb-3 text-secondary"></i>
                    <h5 class="fw-bold">Berita Tidak Ditemukan</h5>
                    <p class="text-muted small mb-0">Maaf, kami tidak dapat menemukan berita yang sesuai dengan filter atau kata kunci pencarian Anda.</p>
                    <a href="index.php?page=berita" class="btn btn-success btn-sm rounded-pill mt-3 px-4">Tampilkan Semua Berita</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
