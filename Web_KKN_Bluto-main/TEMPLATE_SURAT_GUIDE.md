# 📋 Sistem Template Surat yang Dapat Dikustomisasi

## 🎯 Fitur Baru

Sistem ini memungkinkan admin untuk:

1. **Kelola Template Surat** - Membuat dan mengedit template surat secara umum
2. **Edit Draft Individual** - Menyesuaikan draft surat untuk setiap pengajuan
3. **Preview Real-time** - Melihat hasil perubahan secara langsung

---

## 🚀 Cara Menggunakan

### 1️⃣ **Kelola Template Surat (Pengaturan Umum)**

Jika Anda ingin mengubah format **SEMUA** surat jenis tertentu:

1. Masuk ke Panel Admin
2. Klik menu **"📋 Daftar Pengajuan Surat"**
3. Klik tombol **"⚙️ Kelola Template Surat"**
4. Pilih jenis surat yang ingin diubah dari daftar di sebelah kiri
5. Ubah template di tab **"📝 Editor"**
6. Gunakan **"🔤 Variabel"** untuk melihat kode yang bisa digunakan (contoh: `{nama}`, `{nik}`, dst)
7. Lihat preview di tab **"👁️ Preview"**
8. Klik **"💾 Simpan Template"**

**Variabel yang Tersedia:**
- `{nama}` - Nama pemohon
- `{nik}` - NIK pemohon
- `{alamat}` - Alamat pemohon
- `{tanggal}` - Tanggal saat ini
- `{nama_usaha}` - Nama usaha (untuk SKU)
- `{lokasi_usaha}` - Lokasi usaha (untuk SKU)
- `{keterangan}` - Keterangan tambahan
- `{kepala_desa}` - Nama kepala desa
- `{nama_desa}` - Nama desa
- `{nama_kecamatan}` - Nama kecamatan
- `{nama_kabupaten}` - Nama kabupaten

---

### 2️⃣ **Edit Draft Surat Individual**

Jika Anda hanya ingin mengubah **SATU** pengajuan surat tertentu:

1. Masuk ke Panel Admin
2. Klik menu **"📋 Daftar Pengajuan Surat"**
3. Cari pengajuan yang ingin diubah
4. Klik tombol **"✏️ Edit"** pada baris pengajuan tersebut
5. Ubah konten surat sesuai kebutuhan
6. Lihat preview di tab **"👁️ Preview"**
7. Klik **"💾 Simpan Perubahan"**
8. Setelah itu, Anda bisa menyetujui dengan klik **"✓ Setujui"**

---

## 📝 Tips Format HTML

### Format Dasar:
```html
<p>Ini adalah paragraf</p>
<br> <!-- Garis baru -->
<strong>Teks tebal</strong>
<b>Teks tebal</b>
```

### Centering (Rata Tengah):
```html
<div style="text-align:center;">
    Teks yang rata tengah
</div>
```

### Garis Pemisah:
```html
<hr style="border:1px solid #000">
```

### Struktur Surat Standar:
```html
<div style="font-family:serif; max-width:800px; margin:0 auto;">
    <div style="text-align:center;">
        <h2>SURAT KETERANGAN</h2>
        <hr style="border:1px solid #000">
    </div>
    
    <p>Konten surat di sini...</p>
    
    <div style="margin-top:60px; text-align:right;">
        <div>Tempat, {tanggal}</div>
        <br><br><br>
        <div style="text-decoration:underline;">{kepala_desa}</div>
    </div>
</div>
```

---

## 🔄 Alur Kerja

```
Masyarakat Ajukan Surat
        ↓
Admin Terima Pengajuan
        ↓
Admin Edit Draft (Opsional) ✏️
        ↓
Admin Setujui ✓
        ↓
Masyarakat Dapat Surat / Admin Cetak
```

---

## 📊 Jenis Surat yang Bisa Dikustomisasi

1. **Nikah** - Surat Keterangan Nikah
2. **SKU** - Surat Keterangan Usaha
3. **SKTM** - Surat Keterangan Tidak Mampu
4. **SKD** - Surat Keterangan Domisili

---

## 💾 Data Tersimpan

- **Template Umum** → Database SQLite (`template_surat` table)
- **Pengajuan Surat** → File JSON (`data/surat_submissions.json`)
- **Draft Terubah** → Disimpan otomatis saat diklik "Simpan"

---

## ⚠️ Catatan Penting

- Perubahan template umum akan berlaku untuk **SEMUA** pengajuan surat jenis tersebut ke depannya
- Perubahan draft individual hanya berlaku untuk **SATU** pengajuan tersebut
- Pastikan format HTML benar, jika ada error visual, lihat tab "❓ Bantuan"
- Backup data secara berkala untuk keamanan

---

## 🆘 Troubleshooting

**Q: Template tidak muncul di admin?**
A: Pastikan sudah login dan database sudah tersetup. Buka browser console (F12) untuk melihat error.

**Q: Variabel tidak terganti?**
A: Pastikan menggunakan nama variabel yang benar (case-sensitive). Contoh: `{nama}` bukan `{Nama}`.

**Q: Preview kosong?**
A: Klik tombol "🔄 Refresh Preview" atau pindah tab lalu kembali.

**Q: Surat tidak tercetak dengan benar?**
A: Periksa HTML Anda. Gunakan browser print preview (Ctrl+P) sebelum mencetak.
