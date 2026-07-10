<?php

try {
    $stmtUMKM = $koneksi->query("SELECT * FROM umkm ORDER BY id_produk DESC");
    $produk_umkm = $stmtUMKM->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $produk_umkm = [];
}
?>

<div class="bg-warning text-dark py-5 text-center shadow-sm" style="background-image: url('https://www.transparenttextures.com/patterns/food.png');">
    <div class="container my-3">
        <h1 class="display-5 fw-bold"><i class="bi bi-shop"></i> Dapur Lokal Bluto</h1>
        <p class="lead">Dukung UMKM desa dengan membeli produk lokal berkualitas langsung dari pembuatnya.</p>
    </div>
</div>

<div class="container mt-5 mb-5" style="min-height: 50vh;">
    
    
    <div class="row mb-4 align-items-center">
        <div class="col-md-6 mb-3 mb-md-0">
            <span class="fw-bold me-2">Kategori:</span>
            <button class="badge bg-success text-white rounded-pill border-0 px-3 py-2 me-1 category-filter active" data-filter="all">Semua</button>
            <button class="badge bg-light text-dark border rounded-pill px-3 py-2 me-1 category-filter" data-filter="makanan">Makanan/Minuman</button>
            <button class="badge bg-light text-dark border rounded-pill px-3 py-2 category-filter" data-filter="kerajinan">Kerajinan</button>
        </div>
        <div class="col-md-6">
            <div class="input-group">
                <input type="text" id="search-input" class="form-control" placeholder="Cari produk desa...">
                <button class="btn btn-success" id="search-btn" type="button"><i class="bi bi-search"></i> Cari</button>
            </div>
        </div>
    </div>

    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3 g-md-4" id="product-list">
        
        <?php foreach ($produk_umkm as $item): ?>
        <?php 
            $cat_class = 'makanan';
            if (stripos($item['kategori'], 'kriya') !== false || stripos($item['kategori'], 'kerajinan') !== false) {
                $cat_class = 'kerajinan';
            }
        ?>
        <div class="col product-card-col" data-name="<?= strtolower(htmlspecialchars($item['nama'])) ?>" data-category="<?= $cat_class ?>">
            <div class="card h-100 shadow-sm border-0 position-relative">
                
                <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-2 shadow-sm">
                    <?= $item['kategori'] ?>
                </span>
                
                
                <?php
                    $gambarProduk = '';
                    if (!empty($item['gambar'])) {
                        $gambarProduk = preg_match('#^https?://#i', $item['gambar']) ? $item['gambar'] : 'assets/img/' . $item['gambar'];
                    } else {
                        $gambarProduk = 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&q=80&w=300';
                    }
                ?>
                <img src="<?= htmlspecialchars($gambarProduk) ?>" class="card-img-top object-fit-cover" alt="<?= $item['nama'] ?>" style="height: 180px;">
                
                <div class="card-body d-flex flex-column p-3">
                    <h6 class="card-title fw-bold mb-1 lh-sm" style="font-size: 14px; min-height: 34px;">
                        <?= $item['nama'] ?>
                    </h6>
                    <p class="text-success fw-bold mb-2">Rp <?= number_format($item['harga'], 0, ',', '.') ?></p>
                    
                    <p class="card-text text-muted small mb-3 mt-auto">
                        <i class="bi bi-person-fill"></i> <?= $item['penjual'] ?>
                    </p>
                    
                    
                    <?php 
                        $pesan_wa = urlencode("Halo {$item['penjual']}, saya ingin memesan produk: {$item['nama']} yang ada di Website Desa.");
                    ?>
                    <a href="https://wa.me/<?= $item['no_wa'] ?>?text=<?= $pesan_wa ?>" target="_blank" class="btn btn-success btn-sm w-100 rounded-pill">
                        <i class="bi bi-whatsapp"></i> Pesan
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.getElementById('search-input');
    const searchBtn = document.getElementById('search-btn');
    const filters = document.querySelectorAll('.category-filter');
    const products = document.querySelectorAll('.product-card-col');

    let activeFilter = 'all';
    let searchQuery = '';

    function filterProducts() {
        products.forEach(p => {
            const name = p.getAttribute('data-name');
            const category = p.getAttribute('data-category');

            const matchesSearch = name.includes(searchQuery);
            const matchesFilter = activeFilter === 'all' || category === activeFilter;

            if (matchesSearch && matchesFilter) {
                p.style.display = 'block';
            } else {
                p.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', function(e) {
        searchQuery = e.target.value.toLowerCase().trim();
        filterProducts();
    });

    searchBtn.addEventListener('click', function() {
        searchQuery = searchInput.value.toLowerCase().trim();
        filterProducts();
    });

    filters.forEach(btn => {
        btn.addEventListener('click', function() {
            filters.forEach(f => {
                f.classList.remove('bg-success', 'text-white', 'active');
                f.classList.add('bg-light', 'text-dark');
            });
            this.classList.add('bg-success', 'text-white', 'active');
            this.classList.remove('bg-light', 'text-dark');

            activeFilter = this.getAttribute('data-filter');
            filterProducts();
        });
    });
});
</script>
