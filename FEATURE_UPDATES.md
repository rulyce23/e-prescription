# Fitur Baru: Filter Apotek dan Export Excel

## 1. Filter Data Resep Berdasarkan Apotek

### Fitur yang Ditambahkan:
- **Filter dropdown apotek** untuk admin dan dokter di halaman daftar resep
- **Filter status** (pending, diproses, selesai) untuk admin dan dokter
- **Tombol reset filter** untuk menghapus semua filter

### Lokasi:
- Halaman: `resources/views/resep/index.blade.php`
- Controller: `app/Http/Controllers/ResepController.php` (method `index`)

### Cara Penggunaan:
1. Login sebagai admin atau dokter
2. Buka halaman "Daftar Resep"
3. Gunakan dropdown "Filter Apotek" untuk memilih apotek tertentu
4. Gunakan dropdown "Filter Status" untuk memfilter berdasarkan status
5. Klik "Filter" untuk menerapkan filter
6. Klik "Reset" untuk menghapus semua filter

### Keamanan:
- Filter apotek hanya tersedia untuk admin dan dokter
- Apoteker/farmasi otomatis terfilter ke apotek mereka sendiri
- Pasien hanya melihat resep miliknya sendiri

## 2. Export Data Resep ke Excel

### Fitur yang Ditambahkan:
- **Tombol "Export Excel"** di semua halaman resep (index, processing, completed)
- **Export dengan filter** - data yang diexport sesuai dengan filter yang aktif
- **Format Excel yang rapi** dengan header dan styling

### Package yang Digunakan:
- `maatwebsite/excel` untuk export Excel
- File export class: `app/Exports/ResepExport.php`

### Lokasi Tombol Export:
- Halaman utama resep: `resources/views/resep/index.blade.php`
- Halaman processing: `resources/views/resep/processing.blade.php`
- Halaman completed: `resources/views/resep/completed.blade.php`

### Data yang Diexport:
1. **No. Antrian** - Nomor antrian resep
2. **Nama Pasien** - Nama pasien
3. **Apotek** - Nama apotek
4. **Status** - Status resep (Pending/Diproses/Selesai)
5. **Tanggal Pengajuan** - Tanggal pengajuan resep
6. **Tanggal Dibuat** - Tanggal dan waktu pembuatan resep
7. **Keluhan** - Keluhan pasien
8. **Diagnosa** - Diagnosa dokter
9. **Obat Non Racikan** - Daftar obat non racikan dengan quantity
10. **Obat Racikan** - Daftar obat racikan dengan detail
11. **Total Item** - Total jumlah item obat

### Keamanan Export:
- **Pasien**: Hanya bisa export resep miliknya sendiri
- **Apoteker/Farmasi**: Hanya bisa export resep di apoteknya
- **Admin/Dokter**: Bisa export semua resep dengan filter apotek dan status

### Route:
- `GET /resep-export` - Route untuk export Excel
- Parameter query: `apotek_id`, `status`, `start_date`, `end_date`

## 3. Dashboard Pasien - Status Selesai

### Perbaikan:
- Memastikan jumlah resep dengan status "selesai" terhitung dengan benar
- Query: `Resep::where('user_id', $user->id)->completed()->count()`

### Lokasi:
- Controller: `app/Http/Controllers/DashboardController.php`
- View: `resources/views/dashboard.blade.php`

## 4. Instalasi dan Setup

### Package yang Diperlukan:
```bash
composer require maatwebsite/excel
```

### File yang Ditambahkan/Dimodifikasi:
1. **Baru**: `app/Exports/ResepExport.php`
2. **Dimodifikasi**: `app/Http/Controllers/ResepController.php`
3. **Dimodifikasi**: `routes/web.php`
4. **Dimodifikasi**: `resources/views/resep/index.blade.php`
5. **Dimodifikasi**: `resources/views/resep/processing.blade.php`
6. **Dimodifikasi**: `resources/views/resep/completed.blade.php`

### Cache yang Perlu Dibersihkan:
```bash
php artisan config:clear
php artisan route:clear
```

## 5. Penggunaan

### Untuk Admin/Dokter:
1. Buka halaman "Daftar Resep"
2. Gunakan filter apotek dan status sesuai kebutuhan
3. Klik "Export Excel" untuk download data sesuai filter
4. File Excel akan otomatis terdownload dengan nama `resep_YYYY-MM-DD_HH-MM-SS.xlsx`

### Untuk Apoteker/Farmasi:
1. Buka halaman "Daftar Resep" (otomatis terfilter apotek)
2. Klik "Export Excel" untuk download semua data resep di apotek
3. Atau gunakan halaman "Sedang Diproses" / "Selesai" untuk export data spesifik

### Untuk Pasien:
1. Buka halaman "Daftar Resep" (otomatis terfilter resep sendiri)
2. Klik "Export Excel" untuk download semua resep milik sendiri

## 6. Troubleshooting

### Jika Export Tidak Berfungsi:
1. Pastikan package `maatwebsite/excel` sudah terinstall
2. Jalankan `composer dump-autoload`
3. Bersihkan cache: `php artisan config:clear`
4. Cek permission folder storage untuk write access

### Jika Filter Tidak Muncul:
1. Pastikan login sebagai admin atau dokter
2. Cek apakah ada data apotek di database
3. Bersihkan cache browser

### Jika Dashboard Pasien Tidak Menampilkan Jumlah Selesai:
1. Cek apakah ada resep dengan status "selesai" untuk user tersebut
2. Pastikan query di DashboardController berjalan dengan benar 