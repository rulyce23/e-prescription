# 📚 Panduan Lengkap E-Prescription System

## 🎯 Overview

Panduan ini akan membantu Anda memahami dan menggunakan sistem E-Prescription dengan efektif. Sistem ini dirancang untuk mengelola resep digital dengan workflow yang jelas dan keamanan yang tinggi.

---

## 👨‍⚕️ **PANDUAN DOKTER**

### **1. Login dan Dashboard**

#### **Login Pertama Kali**
1. Buka browser dan akses `http://localhost:8000`
2. Masukkan credentials:
   - Email: `dokter@eprescription.com`
   - Password: `password`
3. Jika email belum diverifikasi, Anda akan diarahkan ke halaman verifikasi
4. Cek email dan klik link verifikasi
5. Login kembali

#### **Dashboard Dokter**
Setelah login, Anda akan melihat:
- **Statistik Pribadi**: Jumlah resep yang dibuat, pending approvals, dll
- **Resep Terbaru**: 10 resep terbaru yang Anda buat
- **Pending Approvals**: Resep yang menunggu approval dari dokter lain
- **Quick Actions**: Tombol untuk membuat resep baru

### **2. Membuat Resep Baru**

#### **Langkah 1: Akses Form Resep**
1. Klik tombol "Buat Resep Baru" di dashboard
2. Atau klik menu "Resep" → "Buat Baru"

#### **Langkah 2: Informasi Pasien**
```
Nama Pasien: [Masukkan nama lengkap pasien]
Tanggal Resep: [Pilih tanggal, default hari ini]
Catatan: [Opsional - informasi tambahan]
```

#### **Langkah 3: Tambah Obat Non-Racikan**
1. Klik "Tambah Obat Non-Racikan"
2. Pilih obat dari dropdown (hanya menampilkan yang ada stok)
3. Pilih signa (instruksi penggunaan)
4. Masukkan jumlah
5. Sistem akan menampilkan stok tersedia

**Contoh:**
```
Obat: Paracetamol 500mg
Signa: 3x1 tablet sehari
Jumlah: 10 tablet
```

#### **Langkah 4: Tambah Obat Racikan (Opsional)**
1. Klik "Tambah Racikan"
2. Beri nama racikan (misal: "Racikan Batuk")
3. Pilih signa untuk racikan
4. Tambah komponen obat:
   - Pilih obat
   - Masukkan jumlah
5. Bisa tambah multiple komponen

**Contoh Racikan:**
```
Nama: Racikan Batuk
Signa: 3x1 sendok teh sehari
Komponen:
- Ambroxol 30mg (5 tablet)
- Guaifenesin 100mg (5 tablet)
```

#### **Langkah 5: Preview dan Submit**
1. Lihat preview resep di bagian bawah
2. Pastikan semua data benar
3. Pilih aksi:
   - **"Simpan Draft"**: Simpan sementara, bisa edit nanti
   - **"Ajukan Resep"**: Langsung approve (karena dokter bisa auto-approve)

### **3. Mengelola Resep**

#### **Daftar Resep**
- Klik menu "Resep" → "Daftar Resep"
- Tampilan: Tabel dengan status, nama pasien, tanggal, aksi
- Filter: Bisa filter berdasarkan status

#### **Edit Resep**
- Hanya resep dengan status "Draft" yang bisa diedit
- Klik tombol "Edit" pada resep
- Form sama dengan create, tapi sudah terisi data lama

#### **View Resep**
- Klik tombol "Lihat" untuk melihat detail resep
- Tampilan: Detail lengkap dengan status dan history

#### **Print/PDF**
- Klik tombol "Print" atau "PDF"
- Generate dokumen untuk pasien
- Bisa download atau print langsung

### **4. Approval Workflow**

#### **Sebagai Pembuat Resep**
- Resep yang Anda buat bisa langsung auto-approve
- Status: Draft → Approved

#### **Sebagai Approver**
- Lihat resep pending di dashboard
- Klik "Approve" atau "Reject"
- Jika reject, masukkan alasan

---

## 🏥 **PANDUAN APOTEKER**

### **1. Login dan Dashboard**

#### **Login**
- Email: `apoteker@eprescription.com`
- Password: `password`

#### **Dashboard Apoteker**
- **Resep Approved**: Resep yang sudah disetujui, siap diproses
- **Stok Alert**: Obat dengan stok menipis (≤10)
- **Statistik**: Jumlah resep yang sudah diproses

### **2. Menerima Resep**

#### **Langkah 1: Lihat Resep Approved**
1. Dashboard menampilkan resep dengan status "Approved"
2. Atau klik menu "Resep" → "Daftar Resep"
3. Filter status "Approved"

#### **Langkah 2: Proses Resep**
1. Klik tombol "Terima Resep"
2. Sistem akan:
   - Mengurangi stok otomatis
   - Mengubah status menjadi "Completed"
   - Mencatat waktu penerimaan

#### **Langkah 3: Konfirmasi**
- Resep sudah siap untuk diberikan ke pasien
- Status berubah menjadi "Completed"

### **3. Monitoring Stok**

#### **Stok Alert**
- Dashboard menampilkan obat dengan stok ≤10
- Klik untuk melihat detail
- Update stok jika diperlukan

#### **Update Stok**
1. Klik menu "Obatalkes" (jika punya akses admin)
2. Edit obat yang stoknya menipis
3. Update jumlah stok
4. Simpan perubahan

---

## 👨‍💼 **PANDUAN ADMINISTRATOR**

### **1. Login dan Dashboard**

#### **Login**
- Email: `admin@eprescription.com`
- Password: `password`

#### **Dashboard Admin**
- **Semua Statistik**: Total resep, user, obat, dll
- **Pending Approvals**: Resep yang menunggu approval
- **Low Stock Alerts**: Obat dengan stok menipis
- **Recent Activity**: Aktivitas terbaru sistem

### **2. Manajemen Master Data**

#### **Signa Management**
1. Klik menu "Signa" → "Daftar Signa"
2. **Tambah Signa Baru**:
   - Klik "Tambah Signa"
   - Masukkan nama signa (misal: "3x1 tablet sehari")
   - Simpan

3. **Edit Signa**:
   - Klik tombol "Edit"
   - Ubah nama signa
   - Simpan

4. **Hapus Signa**:
   - Klik tombol "Hapus"
   - Konfirmasi penghapusan

#### **Obatalkes Management**
1. Klik menu "Obatalkes" → "Daftar Obatalkes"
2. **Tambah Obat Baru**:
   - Klik "Tambah Obatalkes"
   - Isi form:
     ```
     Nama: Paracetamol 500mg
     Stok: 100
     Harga: 5000
     ```
   - Simpan

3. **Update Stok**:
   - Klik tombol "Edit"
   - Update jumlah stok
   - Simpan

4. **Monitoring Stok**:
   - Dashboard menampilkan alert stok menipis
   - Filter berdasarkan stok

### **3. User Management**

#### **Lihat Semua User**
- Dashboard menampilkan total user
- Bisa lihat aktivitas user

#### **Monitoring Aktivitas**
- Lihat log aktivitas di storage/logs/laravel.log
- Monitor login/logout user

### **4. System Monitoring**

#### **Dashboard Analytics**
- Total resep per status
- User activity
- Stock levels
- System performance

#### **Reports**
- Export data jika diperlukan
- Generate reports

---

## 👤 **PANDUAN PASIEN**

### **1. Login dan Dashboard**

#### **Login**
- Email: `pasien1@eprescription.com` (atau pasien2-5)
- Password: `password`

#### **Dashboard Pasien**
- **Resep Saya**: Semua resep yang dibuat untuk Anda
- **Status Resep**: Draft, Pending, Approved, Completed
- **Statistik**: Jumlah resep per status

### **2. Melihat Resep**

#### **Daftar Resep**
1. Dashboard menampilkan semua resep Anda
2. Filter berdasarkan status
3. Lihat detail resep

#### **Detail Resep**
- Klik tombol "Lihat" pada resep
- Tampilan detail lengkap:
  - Informasi pasien
  - Daftar obat (non-racikan dan racikan)
  - Status dan history
  - Catatan dokter

#### **Download PDF**
- Klik tombol "PDF" untuk download
- Dokumen untuk keperluan administrasi

---

## 🔧 **FITUR LANJUTAN**

### **1. API Usage**

#### **Login via API**
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "dokter@eprescription.com",
    "password": "password"
  }'
```

#### **Create Prescription via API**
```bash
curl -X POST http://localhost:8000/api/resep \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "nama_pasien": "John Doe",
    "tanggal_resep": "2024-01-15",
    "items": [
      {
        "obatalkes_id": 1,
        "signa_id": 1,
        "qty": 10
      }
    ],
    "action": "submit"
  }'
```

### **2. PDF Generation**

#### **Generate PDF**
- Klik tombol "PDF" pada resep
- Sistem generate PDF dengan format yang rapi
- Bisa download atau print

#### **PDF Content**
- Header dengan logo dan informasi sistem
- Informasi pasien
- Daftar obat (non-racikan dan racikan)
- Signa dan jumlah
- Footer dengan tanggal dan signature

### **3. Email Notifications**

#### **Email Verification**
- Otomatis dikirim saat registrasi
- Link verifikasi valid 60 menit
- Bisa kirim ulang jika expired

#### **Password Reset**
- Otomatis dikirim saat request reset
- Link reset valid 60 menit
- Secure token generation

---

## ⚠️ **TROUBLESHOOTING**

### **Common Issues**

#### **1. Email Tidak Terkirim**
**Gejala**: Email verification/reset tidak masuk
**Solusi**:
- Cek folder spam
- Pastikan Gmail SMTP sudah dikonfigurasi
- Cek app password Gmail

#### **2. Stok Tidak Terupdate**
**Gejala**: Stok tidak berkurang saat approval
**Solusi**:
- Pastikan resep status "Approved"
- Cek log error di storage/logs
- Pastikan obat ada di database

#### **3. PDF Tidak Generate**
**Gejala**: Error saat generate PDF
**Solusi**:
- Install DomPDF: `composer require barryvdh/laravel-dompdf`
- Cek permission storage folder
- Restart server

#### **4. Login Error**
**Gejala**: Tidak bisa login
**Solusi**:
- Pastikan email sudah diverifikasi
- Cek password benar
- Clear cache: `php artisan cache:clear`

### **Performance Tips**

#### **1. Database Optimization**
- Index pada kolom yang sering diquery
- Regular maintenance
- Backup database secara berkala

#### **2. Cache Management**
- Clear cache secara berkala
- Monitor log files
- Optimize queries

#### **3. Security Best Practices**
- Update password secara berkala
- Monitor login attempts
- Backup data penting

---

## 📞 **SUPPORT**

### **Getting Help**
- **Email**: support@eprescription.com
- **Documentation**: Lihat README.md
- **Issues**: Buat issue di GitHub

### **Feature Requests**
- Buat issue dengan label "enhancement"
- Jelaskan use case dengan detail
- Sertakan mockup jika ada

### **Bug Reports**
- Buat issue dengan label "bug"
- Jelaskan steps to reproduce
- Sertakan error message dan screenshot

---

**🎉 Selamat menggunakan E-Prescription System!**

Sistem ini dirancang untuk memudahkan manajemen resep digital dengan workflow yang jelas dan keamanan yang tinggi. Jika ada pertanyaan atau masalah, jangan ragu untuk menghubungi support team. 