<?php

try {
    $stmtGaleri = $koneksi->query("SELECT * FROM galeri ORDER BY id_galeri DESC");
    $galeri_list = $stmtGaleri->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $galeri_list = [];
}
?>

<div class="container mt-2">
    
    <div class="bg-success text-white py-4 px-4 rounded-4 shadow-sm mb-4" style="background: linear-gradient(135deg, var(--emerald-primary) 0%, #115c3a 100%);">
        <h2 class="fw-bold mb-1"><i class="bi bi-images me-2"></i>Galeri Dokumentasi Desa</h2>
        <p class="mb-0 text-white-50 small">Merekam jejak langkah pengabdian KKN UTM Kelompok 42 dan keindahan alam serta kegiatan Desa Bluto.</p>
    </div>

    
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4 mb-5">
        <?php if(count($galeri_list) > 0): ?>
            <?php foreach($galeri_list as $g): ?>
            
            <div class="col">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden hover-lift" style="transition: transform 0.2s ease, box-shadow 0.2s ease;">
                    <?php
                        $gambarGaleri = '';
                        if (!empty($g['gambar'])) {
                            $gambarGaleri = preg_match('#^https?://#i', $g['gambar']) ? $g['gambar'] : 'assets/img/' . $g['gambar'];
                        } else {
                            $gambarGaleri = 'assets/img/stickman-placeholder.svg';
                        }
                    ?>
                    <img src="<?= htmlspecialchars($gambarGaleri) ?>" class="card-img-top object-fit-cover galeri-thumb" alt="<?= htmlspecialchars($g['judul']) ?>" style="height: 220px; cursor: pointer;" data-full="<?= htmlspecialchars($gambarGaleri) ?>" data-title="<?= htmlspecialchars($g['judul']) ?>">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-dark mb-2"><?= htmlspecialchars($g['judul']) ?></h6>
                        <small class="text-muted"><i class="bi bi-clock me-1"></i> <?= htmlspecialchars($g['tanggal']) ?></small>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5 text-muted small w-100">
                <i class="bi bi-images fs-3 d-block mb-2"></i> Belum ada foto dokumentasi desa.
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal preview gambar galeri -->
<div class="modal fade" id="modalPreviewGaleri" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 overflow-hidden">
            <div class="modal-body p-0 position-relative">
                <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
                <img src="" id="modalPreviewImg" class="w-100" style="max-height:80vh; object-fit:contain; background:#000; display:block;">
            </div>
            <div class="modal-footer py-2">
                <div class="me-auto small text-muted" id="modalPreviewTitle"></div>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
        var thumbs = document.querySelectorAll('.galeri-thumb');
        var modalEl = document.getElementById('modalPreviewGaleri');
        if (!modalEl) return;
        var bsModal = new bootstrap.Modal(modalEl);
        var modalImg = document.getElementById('modalPreviewImg');
        var modalTitle = document.getElementById('modalPreviewTitle');

        thumbs.forEach(function(t) {
                t.addEventListener('click', function () {
                        var src = t.getAttribute('data-full') || t.src;
                        var title = t.getAttribute('data-title') || '';
                        modalImg.src = src;
                        modalTitle.textContent = title;
                        bsModal.show();
                });
        });
});
</script>
