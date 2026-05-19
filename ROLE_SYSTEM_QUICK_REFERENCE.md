# 🎯 Role-Based View System - Quick Reference

## 📊 Tampilan per Role

### 1️⃣ SALES (Penjualan) - 🟢 Hijau
```
Dashboard Penjualan Personal
├── Penjualan Hari Ini
├── Penjualan Bulan Ini
├── Pelanggan Pribadi
└── Aksi Cepat
    ├── Buat Penjualan Baru
    ├── Tambah Pelanggan
    └── Cari Harga Produk
```

**Sidebar Menu:**
- Dashboard
- Penjualan (hanya milik sendiri)
- Pelanggan (hanya milik sendiri)

**Fitur:**
- Lihat penjualan sendiri
- Edit penjualan dalam 24 jam
- Buat pelanggan baru
- Lookup harga

---

### 2️⃣ SUPERVISOR (Pimpinan) - 🟣 Ungu
```
Dashboard Supervisor (Tim)
├── Total Penjualan Tim
├── Anggota Tim
├── Penjualan Pribadi
├── Pencapaian Target
└── Performa Salesman (5 terbaik)
    └── Aksi Cepat
        ├── Kelola Tim
        ├── Buat Penjualan
        ├── Lihat Salesman
        └── Daftar Pelanggan
```

**Sidebar Menu:**
- Dashboard
- Penjualan (tim + pribadi)
- Pelanggan (tim + pribadi)
- Salesman (tim)
- Tim Saya

**Fitur:**
- Lihat performa tim
- Kelola anggota tim
- Laporan tim
- Edit penjualan tim

---

### 3️⃣ MANAGER (Manajemen) - 🔵 Biru
```
Dashboard Manager (Bisnis)
├── Total Penjualan Keseluruhan
├── Total Pesanan
├── Pelanggan Aktif
├── Produk Aktif
├── Ringkasan Tim (Table)
├── Manajemen Cepat
│   ├── Kelola Produk
│   ├── Supplier
│   ├── Atur Harga
│   ├── Kelola Area
│   └── Lihat Penjualan
└── Ringkasan Keuangan
    ├── Pemasukan
    ├── Pengeluaran
    └── Saldo Bersih
```

**Sidebar Menu:**
- Dashboard
- Penjualan + Arus Kas
- Pelanggan + Produk + Supplier + Harga
- Salesman + Tim Saya
- Area

**Fitur:**
- Lihat semua data
- Kelola produk/supplier/harga
- Lihat arus kas
- Full reporting

---

### 4️⃣ ADMIN (Administrator) - 🔴 Merah
```
Admin Control Center
├── System Stats (5 KPIs)
│   ├── Total Revenue
│   ├── Total Orders
│   ├── Active Users
│   ├── Total Customers
│   └── Products
├── Data Master Management
│   ├── Produk
│   ├── Supplier
│   ├── Harga
│   └── Area
├── Transaction Management
│   ├── Penjualan
│   ├── Arus Kas
│   └── Pelanggan
├── Organization Management
│   ├── Salesman
│   └── Tim
├── System Administration
│   ├── User & Role
│   └── Pengaturan Sistem (coming soon)
└── System Overview
    ├── Data Records
    ├── Activity
    └── Health Status
```

**Sidebar Menu:**
- Dashboard
- **Semua** menu (Penjualan, Arus Kas, Pelanggan, Produk, Supplier, Harga, Salesman, Tim, Area)
- **User & Role** (Admin Only!)

**Fitur:**
- Full CRUD semua modul
- User management
- System monitoring
- Full access

---

## 🛠️ Implementation Details

### RoleHelper Usage
```php
use App\Helpers\RoleHelper;

// Check role
RoleHelper::isAdmin();           // true/false
RoleHelper::isManager();         // true/false
RoleHelper::isSupervisor();      // true/false
RoleHelper::isSales();           // true/false

// Check multiple
RoleHelper::hasRole('admin', 'manager');

// Get info
RoleHelper::getCurrentRole();    // 'admin'
RoleHelper::getRoleLabel();      // 'Administrator'
RoleHelper::getRoleIcon();       // 'shield-alert'
RoleHelper::getRoleBadgeColor(); // 'bg-red-100 text-red-800...'
```

### Blade Component
```blade
<x-role-check roles="admin,manager">
    <button>Edit</button>
</x-role-check>

<x-role-check role="sales">
    <div>Sales only</div>
</x-role-check>
```

### Route Protection
```php
Route::middleware('role:admin')->group(function () {
    // admin routes
});

Route::middleware('role:manager,admin')->group(function () {
    // manager and admin routes
});
```

---

## 📁 File Locations

**Helper:**
- `app/Helpers/RoleHelper.php`

**Components:**
- `app/View/Components/RoleCheck.php`
- `resources/views/components/role-check.blade.php`

**Dashboards:**
- `resources/views/dashboards/admin.blade.php`
- `resources/views/dashboards/manager.blade.php`
- `resources/views/dashboards/supervisor.blade.php`
- `resources/views/dashboards/sales.blade.php`

**Modified:**
- `resources/views/layouts/sidebar.blade.php` (role-aware)
- `app/Http/Controllers/DashboardController.php` (route to role-specific views)

**Documentation:**
- `Referensi/ROLE_BASED_ACCESS_CONTROL.md` (full guide)

---

## 🔑 Role Badge Colors

| Role | Color | Icon |
|------|-------|------|
| Admin | 🔴 Red (bg-red-500/20) | shield-alert |
| Manager | 🔵 Blue (bg-blue-500/20) | briefcase |
| Supervisor | 🟣 Purple (bg-purple-500/20) | users |
| Sales | 🟢 Green (bg-green-500/20) | shopping-bag |

---

## ✨ Features

✅ Dynamic sidebar menu based on role
✅ Role-specific dashboards with relevant KPIs
✅ Role badge in profile card
✅ Helper functions for easy role checking
✅ Blade component for conditional rendering
✅ Existing RoleMiddleware integration
✅ Policy-based authorization (existing)
✅ Comprehensive documentation
✅ Easy to extend

---

## 🚀 Quick Start

1. **Login dengan berbagai role** untuk melihat perbedaan tampilan
2. **Sidebar** otomatis menyesuaikan menu
3. **Dashboard** menampilkan data relevan per role
4. **Profile card** menunjukkan role dengan warna unik

---

Last Updated: {{ Now }}
Version: 1.0
