# Software Requirements Specification (SRS)

## 1. Pendahuluan
### 1.1 Tujuan
Dokumen ini mendefinisikan kebutuhan sistem aplikasi penjualan distributor, reimplementasi dari sistem DataFlex/DOS lama ke Laravel 12 + Tailwind CDN.

### 1.2 Lingkup
Sistem ini mencakup pengelolaan master data (barang, customer, harga), transaksi penjualan, diskon, pajak, laporan, dan fitur pendukung lain sesuai sistem lama.

### 1.3 Definisi
- User: Pengguna aplikasi
- Admin: Pengelola sistem
- Faktur: Dokumen transaksi penjualan

## 2. Deskripsi Umum
### 2.1 Perspektif Sistem
Aplikasi berbasis web, multi-user, dengan database MySQL.

### 2.2 Fungsi Utama
- Manajemen master data (barang, customer, harga)
- Input & proses transaksi penjualan
- Pengelolaan diskon, pajak, bonus
- Laporan penjualan & rekapitulasi
- User management & otorisasi

### 2.3 Karakteristik Pengguna
- Admin
- Sales
- Supervisor

### 2.4 Batasan
- Data lama dapat dimigrasi
- Akses berbasis web

## 3. Kebutuhan Fungsional
### 3.1 Master Data
- Barang: CRUD barang, kategori, satuan
- Customer: CRUD customer, wilayah
- Harga: CRUD harga, grup customer

### 3.2 Transaksi Penjualan
- Input faktur penjualan (header & detail)
- Perhitungan otomatis diskon, pajak, bonus
- Validasi stok & harga
- Cetak faktur

### 3.3 Laporan
- Laporan penjualan per periode
- Laporan rekap per customer/barang
- Export data (Excel/PDF)

### 3.4 User Management
- CRUD user
- Hak akses per modul

## 4. Kebutuhan Non-Fungsional
- Responsive UI (Tailwind)
- Keamanan data
- Backup & restore
- Audit log

## 5. Diagram Alur (Flowchart)
(Lampiran: akan dibuat pada tahap desain)

## 6. Lampiran
- Struktur tabel lama & mapping ke MySQL
- Contoh format data
- Daftar field penting

---

Dokumen ini akan diperbarui seiring proses pengembangan.