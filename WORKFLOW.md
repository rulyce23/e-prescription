# Workflow Sistem E-Prescription

## 🔄 Alur Kerja Sistem

### 1. Role dan Hak Akses

#### Admin/Superadmin
- **Akses**: Semua fitur sistem
- **Tugas**: 
  - Kelola master data (signa, obatalkes)
  - Monitor semua resep
  - Kelola user dan role
  - Lihat statistik lengkap
  - **Buat resep** dan **approve resep**

#### Dokter
- **Akses**: Membuat resep dan approval resep
- **Tugas**:
  - Buat resep baru (draft)
  - Edit resep draft/ditolak
  - Ajukan resep untuk approval
  - **Approve/reject resep** dari dokter lain
  - Monitor semua resep untuk approval

#### Apoteker
- **Akses**: Hanya menerima resep yang sudah disetujui
- **Tugas**:
  - Lihat resep yang sudah disetujui
  - Terima dan selesaikan resep
  - Print resep selesai
  - **TIDAK BISA** buat atau approve resep

### 2. Status Resep

```
Draft → Diajukan → Disetujui → Selesai
   ↓        ↓         ↓
Ditolak ←─────┘
```

#### Draft
- Resep masih dalam penyusunan
- Hanya bisa diakses oleh pembuat (dokter/admin)
- Bisa diedit dan dihapus
- Tidak mengurangi stok

#### Diajukan
- Resep sudah diajukan untuk approval
- Menunggu review dari dokter/admin
- Stok sudah dikurangi
- Tidak bisa diedit lagi

#### Disetujui
- Resep disetujui oleh dokter/admin
- Siap untuk diterima apoteker
- Bisa dicetak

#### Selesai
- Resep sudah diterima dan diselesaikan apoteker
- Status final
- Bisa dicetak

#### Ditolak
- Resep ditolak oleh dokter/admin
- Stok dikembalikan
- Bisa diedit ulang oleh pembuat

### 3. Workflow Detail

#### A. Dokter/Admin Membuat Resep

1. **Login sebagai Dokter/Admin**
   ```
   Email: dokter@example.com / admin@example.com
   Password: password
   ```

2. **Buat Resep Baru**
   - Klik "Buat Resep Baru"
   - Isi informasi pasien
   - Tambah obat non-racikan (opsional)
   - Tambah obat racikan (opsional)
   - Preview resep

3. **Simpan atau Ajukan**
   - **Simpan Draft**: Resep disimpan sebagai draft
   - **Ajukan Resep**: Resep langsung diajukan untuk approval

#### B. Dokter/Admin Review Resep

1. **Login sebagai Dokter/Admin**
   ```
   Email: dokter@example.com / admin@example.com
   Password: password
   ```

2. **Lihat Daftar Resep**
   - Dashboard menampilkan semua resep
   - Klik "Lihat Semua" untuk daftar lengkap

3. **Review Resep**
   - Klik detail resep untuk review
   - Periksa informasi pasien
   - Periksa item obat dan stok
   - Periksa racikan (jika ada)

4. **Setujui atau Tolak**
   - **Setujui**: Resep berstatus "Disetujui"
   - **Tolak**: Resep berstatus "Ditolak", stok dikembalikan

#### C. Apoteker Menerima Resep

1. **Login sebagai Apoteker**
   ```
   Email: apoteker@example.com
   Password: password
   ```

2. **Lihat Resep Disetujui**
   - Dashboard hanya menampilkan resep disetujui
   - Klik "Lihat Semua" untuk daftar lengkap

3. **Terima Resep**
   - Klik detail resep untuk review
   - Klik "Terima Resep" untuk menyelesaikan
   - Resep berstatus "Selesai"

4. **Print Resep**
   - Klik tombol "Print" untuk cetak
   - Resep dibuka di tab baru untuk print

### 4. Validasi dan Keamanan

#### Validasi Stok
- Stok dicek saat pengajuan resep
- Jika stok tidak mencukupi, resep tidak bisa diajukan
- Stok dikurangi otomatis saat resep diajukan
- Stok dikembalikan jika resep ditolak

#### Validasi Role
- Setiap aksi divalidasi berdasarkan role user
- Dokter/Admin bisa akses semua resep dan approval
- Apoteker hanya bisa akses resep disetujui
- Hanya pembuat resep yang bisa edit draft/ditolak

#### Validasi Status
- Resep draft hanya bisa diedit oleh pembuat
- Resep yang sudah diajukan tidak bisa diedit
- Hanya resep disetujui/selesai yang bisa dicetak
- Hanya apoteker yang bisa menerima resep

### 5. Notifikasi dan Feedback

#### Success Messages
- "Draft resep berhasil disimpan"
- "Resep berhasil diajukan"
- "Resep berhasil disetujui"
- "Resep berhasil ditolak"
- "Resep berhasil diterima dan diselesaikan"

#### Error Messages
- "Stok tidak mencukupi untuk obat: [nama obat]"
- "Anda tidak memiliki izin untuk [aksi]"
- "Resep tidak ditemukan"

### 6. Dashboard Berdasarkan Role

#### Admin Dashboard
- Total semua resep
- Statistik per status
- Resep terbaru dari semua user
- Alert stok habis dan rendah
- Quick action: Buat resep, kelola master data

#### Dokter Dashboard
- Total semua resep (untuk approval)
- Statistik per status
- Resep terbaru untuk review
- Quick action: Buat resep

#### Apoteker Dashboard
- Total resep disetujui/selesai
- Resep yang siap diterima
- Alert stok untuk persiapan
- Quick action: Lihat resep

### 7. Best Practices

#### Untuk Dokter/Admin
1. Selalu preview resep sebelum ajukan
2. Periksa stok sebelum ajukan resep
3. Review resep dengan teliti sebelum approve
4. Monitor resep yang diajukan secara berkala

#### Untuk Apoteker
1. Review resep yang disetujui dengan teliti
2. Periksa ketersediaan stok untuk persiapan
3. Terima resep dengan cepat untuk efisiensi
4. Print resep dengan format yang rapi

#### Untuk Admin
1. Monitor stok secara berkala
2. Update master data secara rutin
3. Backup database secara berkala
4. Monitor aktivitas user

### 8. Troubleshooting

#### Resep Tidak Bisa Diajukan
- Periksa stok obat
- Pastikan semua field terisi
- Periksa koneksi database

#### Stok Tidak Terupdate
- Refresh halaman
- Periksa log error
- Hubungi admin

#### Print Tidak Muncul
- Periksa popup blocker
- Pastikan browser mendukung print
- Coba browser berbeda

---

**Workflow ini memastikan sistem berjalan dengan aman dan efisien sesuai dengan kebutuhan klinis.** 