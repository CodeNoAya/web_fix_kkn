<?php

$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';

try {
    $query = "SELECT berita.*, kategori_berita.nama_kategori 
              FROM berita 
              JOIN kategori_berita ON berita.id_kategori = kategori_berita.id_kategori
              WHERE berita.slug = :slug";
    $stmt = $koneksi->prepare($query);
    $stmt->execute([':slug' => $slug]);
    $berita = $stmt->fetch(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $berita = null;
}

try {
    $queryRec = "SELECT * FROM berita WHERE slug != :slug ORDER BY tanggal_publikasi DESC LIMIT 3";
    $stmtRec = $koneksi->prepare($queryRec);
    $stmtRec->execute([':slug' => $slug]);
    $rekomendasi = $stmtRec->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $rekomendasi = [];
}
?>

<div class="container mt-2 mb-5">
    <?php if ($berita): ?>
        
        <div class="mb-4">
            <a href="index.php?page=berita" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Berita
            </a>
        </div>

        <div class="row g-4">
            
            <div class="col-lg-8">
                <article class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    
                    <?php 
                        $gambar = !empty($berita['gambar_cover']) ? 'assets/img/'.$berita['gambar_cover'] : 'https://images.unsplash.com/photo-1517048676732-d65bc937f952?auto=format&fit=crop&q=80';
                    ?>
                    <img src="<?= $gambar ?>" class="w-100 object-fit-cover" alt="<?= htmlspecialchars($berita['judul']) ?>" style="max-height: 400px;">
                    
                    <div class="card-body p-4 p-md-5">
                        
                        <div class="d-flex align-items-center mb-3 flex-wrap gap-2 text-muted small">
                            <span class="badge bg-success px-2.5 py-1.5 fw-semibold text-white"><?= htmlspecialchars($berita['nama_kategori']) ?></span>
                            <span class="mx-1">&bull;</span>
                            <span><i class="bi bi-calendar3 me-1"></i><?= date('d F Y', strtotime($berita['tanggal_publikasi'])) ?></span>
                            <span class="mx-1">&bull;</span>
                            <span><i class="bi bi-person me-1"></i>Penulis: Admin</span>
                        </div>

                        
                        <h2 class="fw-bold text-dark lh-base mb-4"><?= htmlspecialchars($berita['judul']) ?></h2>

                        
                        <div class="text-muted lh-lg fs-6 border-top pt-4">
                            
                            <?= nl2br(htmlspecialchars($berita['isi'])) ?>
                        </div>

                        
                        <div class="border-top mt-5 pt-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <span class="fw-bold text-secondary small"><i class="bi bi-share me-1"></i> Bagikan Berita:</span>
                            <div class="d-flex gap-2">
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]") ?>" target="_blank" class="btn btn-sm btn-outline-primary rounded-circle"><i class="bi bi-facebook"></i></a>
                                <a href="https://api.whatsapp.com/send?text=<?= urlencode($berita['judul'] . " - http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]") ?>" target="_blank" class="btn btn-sm btn-outline-success rounded-circle"><i class="bi bi-whatsapp"></i></a>
                                <a href="https://twitter.com/intent/tweet?text=<?= urlencode($berita['judul']) ?>&url=<?= urlencode("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]") ?>" target="_blank" class="btn btn-sm btn-outline-dark rounded-circle"><i class="bi bi-twitter-x"></i></a>
                            </div>
                        </div>
                    </div>
                </article>
            </div>

            
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold text-success border-bottom pb-2 mb-3">
                            <i class="bi bi-star-fill me-2 text-warning"></i>Rekomendasi Lainnya
                        </h5>
                        
                        <?php if (count($rekomendasi) > 0): ?>
                            <div class="d-flex flex-column gap-3">
                                <?php foreach ($rekomendasi as $rec): ?>
                                    <div class="d-flex align-items-start border-bottom pb-3">
                                        <?php 
                                            $gambar_rec = !empty($rec['gambar_cover']) ? 'assets/img/'.$rec['gambar_cover'] : 'https://images.unsplash.com/photo-1517048676732-d65bc937f952?auto=format&fit=crop&q=20&w=150';
                                        ?>
                                        <img src="<?= $gambar_rec ?>" class="rounded object-fit-cover me-3 border" style="width: 70px; height: 70px;" alt="<?= htmlspecialchars($rec['judul']) ?>">
                                        <div>
                                            <a href="index.php?page=detail_berita&slug=<?= $rec['slug'] ?>" class="text-decoration-none text-dark fw-semibold small hover-link d-block lh-base mb-1" style="min-height: 38px;">
                                                <?= htmlspecialchars($rec['judul']) ?>
                                            </a>
                                            <small class="text-muted" style="font-size: 11px;">
                                                <i class="bi bi-calendar3 me-1"></i>
                                                <?= date('d M Y', strtotime($rec['tanggal_publikasi'])) ?>
                                            </small>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted small mb-0">Belum ada berita rekomendasi lainnya.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

    <?php else: ?>
        
        <div class="text-center py-5">
            <div class="alert alert-danger py-5 rounded-4 bg-white border border-danger">
                <i class="bi bi-exclamation-triangle fs-1 d-block mb-3 text-danger"></i>
                <h4 class="fw-bold text-danger">Berita Tidak Ditemukan</h4>
                <p class="text-muted mb-4">Maaf, tautan atau artikel berita yang Anda cari tidak tersedia atau telah dihapus.</p>
                <a href="index.php?page=berita" class="btn btn-success btn-sm rounded-pill px-4">Kembali ke Daftar Berita</a>
            </div>
        </div>
    <?php endif; ?>
</div>
