# Sistem Penjualan (Laravel 12, PHP 8.2)

Implementasi fitur mengacu ke:
- `Referensi/DATABASE_DESIGN.md`
- `Referensi/SRS.md`

## Fitur Utama
- Master data: **Supplier, Salesman, Customer (kota & grup), Produk, Harga (per grup customer + tanggal efektif)**
- Transaksi: **Faktur penjualan (header + detail item)**, diskon/pajak/bonus, **validasi stok**, **cetak invoice PDF**
- Laporan: rekap penjualan, export **PDF** dan **CSV**
- User management: **login/register**, profile, **role** (`admin|supervisor|sales`) + menu **User & Role** (admin-only)
- Audit log: tabel `audit_logs` sudah tersedia (siap dipakai untuk pencatatan aktivitas)

## Cara Menjalankan
```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
npm install
npm run dev
php artisan serve
```

## Akun Default (Seeder)
- Email: `admin@mail.com`
- Password: `password`
- Role: `admin`

## Modul PDF (PHP 8.2)
Project ini pakai **`barryvdh/laravel-dompdf`** (kompatibel PHP 8.2) untuk:
- Cetak invoice: menu Penjualan → ikon printer
- Export laporan penjualan: PDF

## Catatan Export Excel
Excel biasanya butuh ekstensi `ext-zip` (PHP Zip). Supaya aman di PHP 8.2 tanpa konfigurasi tambahan, export disediakan sebagai **CSV**.

