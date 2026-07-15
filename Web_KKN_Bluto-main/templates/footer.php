<?php

$settings_file = 'config/web_settings.json';
if (file_exists($settings_file)) {
    $settings = json_decode(file_get_contents($settings_file), true);
} else {
    $settings = [
        'kontak' => [
            'alamat' => 'Jl. Raya Bluto, Kec. Bluto, Kab. Sumenep, Jawa Timur, 69466',
            'email' => 'pemdes@bluto.desa.id',
            'telepon' => '081234567890'
        ]
    ];
}
?>
    </main>

    
    <footer class="bg-dark text-white pt-5 pb-3 mt-auto" style="background: linear-gradient(180deg, #0d1b2a 0%, #050b14 100%); border-top: 4px solid #198754;">
        <div class="container">
            <div class="row g-4">
                
                <div class="col-lg-4 col-md-6">
                    <h5 class="fw-bold mb-3 text-success">
                        <i class="bi bi-house-door-fill me-2"></i>Pemerintah Desa Bluto
                    </h5>
                    <p class="text-white-50 small">
                        Website resmi Pemerintah Desa Bluto, Kecamatan Bluto, Kabupaten Sumenep, Jawa Timur. Wadah pelayanan mandiri, transparansi publik, dan pusat informasi terpercaya bagi seluruh warga desa.
                    </p>
                    <div class="mt-3">
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle me-2"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle me-2"><i class="bi bi-youtube"></i></a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle me-2"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle"><i class="bi bi-twitter-x"></i></a>
                    </div>
                </div>

                
                <div class="col-lg-4 col-md-6">
                    <h5 class="fw-bold mb-3 text-success">
                        <i class="bi bi-geo-alt-fill me-2"></i>Kontak Balai Desa
                    </h5>
                    <ul class="list-unstyled text-white-50 small">
                        <li class="mb-2 d-flex align-items-start">
                            <i class="bi bi-geo-alt text-success me-2 mt-1"></i>
                            <span><?= htmlspecialchars($settings['kontak']['alamat']) ?></span>
                        </li>
                        <li class="mb-2 d-flex align-items-center">
                            <i class="bi bi-envelope text-success me-2"></i>
                            <span><?= htmlspecialchars($settings['kontak']['email']) ?></span>
                        </li>
                        <li class="mb-2 d-flex align-items-center">
                            <i class="bi bi-telephone text-success me-2"></i>
                            <span><?= htmlspecialchars($settings['kontak']['telepon']) ?></span>
                        </li>
                    </ul>
                </div>

                
                <div class="col-lg-4 col-md-12">
                    <h5 class="fw-bold mb-3 text-success">
                        <i class="bi bi-link-45deg me-2"></i>Tautan Cepat
                    </h5>
                    <div class="row">
                        <div class="col-6">
                            <ul class="list-unstyled text-white-50 small">
                                <li class="mb-2"><a href="index.php?page=home" class="text-white-50 text-decoration-none hover-link">Beranda</a></li>
                                <li class="mb-2"><a href="index.php?page=profil" class="text-white-50 text-decoration-none hover-link">Profil Desa</a></li>
                                <li class="mb-2"><a href="index.php?page=berita" class="text-white-50 text-decoration-none hover-link">Berita & Acara</a></li>
                            </ul>
                        </div>
                        <div class="col-6">
                            <ul class="list-unstyled text-white-50 small">
                                <li class="mb-2"><a href="index.php?page=layanan" class="text-white-50 text-decoration-none hover-link">Layanan Publik</a></li>
                                <li class="mb-2"><a href="index.php?page=dapur-lokal" class="text-white-50 text-decoration-none hover-link">Dapur Lokal</a></li>
                                <li class="mb-2"><a href="index.php?page=kontak" class="text-white-50 text-decoration-none hover-link">Hubungi Kami</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-4 border-light opacity-25">

            
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
                    <p class="mb-0 text-white-50 small">
                        &copy; <?= date('Y') ?> Pemerintah Desa Bluto. Hak Cipta Dilindungi Undang-Undang.
                    </p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="mb-0 text-white-50 small">
                        Didesain & Dikembangkan oleh <strong class="text-white">KKN UTM Kelompok 42</strong>.
                    </p>
                </div>
            </div>
        </div>
    </footer>

    </div> 
    </div> 
    </div> 

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        function updateClock() {
            const now = new Date();
            let hours = now.getHours().toString().padStart(2, '0');
            let minutes = now.getMinutes().toString().padStart(2, '0');
            let seconds = now.getSeconds().toString().padStart(2, '0');
            
            const clockElement = document.getElementById('digital-clock');
            if (clockElement) {
                clockElement.textContent = hours + ":" + minutes + ":" + seconds + " WIB";
            }
        }
        setInterval(updateClock, 1000);
        updateClock();

        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        
        const now = new Date();
        const dayName = days[now.getDay()];
        const dateNum = now.getDate();
        const monthName = months[now.getMonth()];
        const yearNum = now.getFullYear();
        
        const dateElement = document.getElementById('current-date');
        if (dateElement) {
            dateElement.textContent = dayName + ", " + dateNum + " " + monthName + " " + yearNum;
        }

        const prayerTimes = {
            'subuh': '04:18',
            'dzuhur': '11:34',
            'ashar': '14:55',
            'maghrib': '17:28',
            'isya': '18:42'
        };

        for (const [prayer, time] of Object.entries(prayerTimes)) {
            const el = document.getElementById('prayer-' + prayer);
            if (el) {
                el.textContent = time;
            }
        }

        const coordinateBluto = [-7.106113532162065, 113.80665145030012]; // Koordinat Balai Desa Bluto (diperbarui)
        
        const mapHomeEl = document.getElementById('map-home');
        if (mapHomeEl) {
            const mapHome = L.map('map-home', {
                scrollWheelZoom: true,
                dragging: true,
                tap: true
            }).setView(coordinateBluto, 14);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(mapHome);
            
            L.marker(coordinateBluto).addTo(mapHome)
                .bindPopup('<b>Kantor Balai Desa Bluto</b><br>Kecamatan Bluto, Sumenep.')
                .openPopup();
        }

        const mapKontakEl = document.getElementById('map-kontak');
        if (mapKontakEl) {
            const mapKontak = L.map('map-kontak', {
                scrollWheelZoom: true,
                dragging: true,
                tap: true
            }).setView(coordinateBluto, 15);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(mapKontak);
            
            L.marker(coordinateBluto).addTo(mapKontak)
                .bindPopup('<b>Kantor Balai Desa Bluto</b><br>Jl. Raya Bluto, Kec. Bluto, Kab. Sumenep, Jawa Timur.')
                .openPopup();
        }
    });
    </script>
</body>
</html>
