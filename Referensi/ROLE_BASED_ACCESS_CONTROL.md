# Role-Based Access Control (RBAC) System

## Ringkasan
Sistem penjualan ini mengimplementasikan 4 role berbeda dengan tingkat akses yang berbeda-beda:

### 1. **SALES (Penjualan)** 🟢
**Level Akses: Terbatas (Diri Sendiri)**

#### Apa yang bisa dilihat/akses:
- ✅ Dashboard Penjualan Personal
- ✅ Membuat penjualan baru
- ✅ Melihat penjualan pribadi
- ✅ Melihat pelanggan pribadi
- ✅ Lookup harga produk
- ✅ Edit profil pribadi
- ✅ Melihat transaksi pribadi

#### Menu Sidebar:
- Dashboard
- Transaksi → Penjualan (hanya milik sendiri)
- Data Master → Pelanggan (hanya milik sendiri)

#### Tidak bisa akses:
- ❌ Data penjualan orang lain
- ❌ Pelanggan orang lain
- ❌ Kelola produk/supplier
- ❌ Kelola user
- ❌ Laporan keseluruhan

---

### 2. **SUPERVISOR (Pimpinan)** 🟣
**Level Akses: Tim (Diri + Subordinat)**

#### Apa yang bisa dilihat/akses:
- ✅ Dashboard Supervisor (Team Overview)
- ✅ Melihat penjualan tim + pribadi
- ✅ Membuat penjualan
- ✅ Melihat pelanggan tim + pribadi
- ✅ Membuat pelanggan baru
- ✅ Kelola tim (Salesman subordinat)
- ✅ Lookup harga produk
- ✅ Edit profil pribadi
- ✅ Laporan penjualan tim

#### Menu Sidebar:
- Dashboard
- Transaksi → Penjualan (tim + pribadi)
- Data Master → Pelanggan (tim + pribadi)
- Manajemen Tim → Salesman, Tim Saya

#### Tidak bisa akses:
- ❌ Penjualan di tim lain
- ❌ Kelola produk/supplier/harga
- ❌ Kelola user
- ❌ Arus kas

---

### 3. **MANAGER (Manajemen)** 🔵
**Level Akses: Penuh (Baca) + Kontrol Data Master**

#### Apa yang bisa dilihat/akses:
- ✅ Dashboard Manager (Full Overview)
- ✅ Semua penjualan (baca)
- ✅ Semua pelanggan (baca)
- ✅ Kelola produk (CRUD)
- ✅ Kelola supplier (CRUD)
- ✅ Kelola harga (CRUD)
- ✅ Kelola area
- ✅ Kelola salesman (baca)
- ✅ Kelola tim
- ✅ Lihat arus kas
- ✅ Laporan lengkap

#### Menu Sidebar:
- Dashboard
- Transaksi → Penjualan, Arus Kas
- Data Master → Pelanggan, Produk, Supplier, Harga
- Manajemen Tim → Salesman, Tim Saya
- Pengaturan → Area

#### Tidak bisa akses:
- ❌ Kelola user & role
- ❌ Edit penjualan orang lain (hanya baca)
- ❌ Hapus data transaksi

---

### 4. **ADMIN (Administrator)** 🔴
**Level Akses: PENUH (Full Control)**

#### Apa yang bisa dilihat/akses:
- ✅ **SEMUA MENU DAN FITUR**
- ✅ Dashboard Admin (System Overview)
- ✅ Semua penjualan (CRUD)
- ✅ Semua pelanggan (CRUD)
- ✅ Kelola produk (CRUD)
- ✅ Kelola supplier (CRUD)
- ✅ Kelola harga (CRUD)
- ✅ Kelola area (CRUD)
- ✅ Kelola salesman (CRUD)
- ✅ Kelola tim (CRUD)
- ✅ Kelola arus kas (CRUD)
- ✅ **Kelola user & role** (Hanya Admin)
- ✅ Semua laporan
- ✅ Sistem monitoring

#### Menu Sidebar:
- Dashboard
- Transaksi → Penjualan, Arus Kas
- Data Master → Pelanggan, Produk, Supplier, Harga
- Manajemen Tim → Salesman, Tim Saya
- Pengaturan → Area
- Administrator → User & Role

---

## Fitur Implementasi

### 1. **RoleHelper Class** (`app/Helpers/RoleHelper.php`)
Menyediakan fungsi-fungsi helper untuk cek role:

```php
use App\Helpers\RoleHelper;

// Check role individual
RoleHelper::isAdmin();        // bool
RoleHelper::isManager();      // bool
RoleHelper::isSupervisor();   // bool
RoleHelper::isSales();        // bool

// Check multiple roles
RoleHelper::hasRole('admin', 'manager');  // bool

// Cek akses menu
RoleHelper::canAccessMenu('product');  // bool

// Get accessible menus
RoleHelper::getAccessibleMenus();  // array

// Get role info
RoleHelper::getCurrentRole();       // string
RoleHelper::getRoleLabel();         // string (e.g., "Administrator")
RoleHelper::getRoleIcon();          // string (e.g., "shield-alert")
RoleHelper::getRoleBadgeColor();    // string (CSS class)
```

### 2. **Sidebar Dinamis** (`resources/views/layouts/sidebar.blade.php`)
Sidebar otomatis menyesuaikan menu berdasarkan role:
- Menu Utama (semua role)
- Menu Transaksi (sales, supervisor, manager, admin)
- Menu Data Master (berbeda per role)
- Menu Manajemen Tim (supervisor, manager, admin)
- Menu Pengaturan (manager, admin)
- Menu Administrator (admin only)

### 3. **Role-Specific Dashboards**
Setiap role memiliki dashboard khusus di folder `resources/views/dashboards/`:

- `admin.blade.php` - Full system overview
- `manager.blade.php` - Business intelligence
- `supervisor.blade.php` - Team performance
- `sales.blade.php` - Personal sales tracking

### 4. **RoleMiddleware** (`app/Http/Middleware/RoleMiddleware.php`)
Middleware untuk proteksi route:

```php
// Route protection
Route::get('/admin', [AdminController::class, 'index'])
    ->middleware('role:admin');

Route::get('/manager', [ManagerController::class, 'index'])
    ->middleware('role:manager,admin');
```

### 5. **Role Badge di Profile**
Profile card di sidebar menampilkan role badge dengan:
- Warna berbeda per role
- Icon unik per role
- Label jelas (Administrator, Manager, Supervisor, Penjualan)

---

## Blade Components

### RoleCheck Component
Untuk menampilkan/menyembunyikan konten berdasarkan role:

```blade
<x-role-check roles="admin,manager">
    <div>Hanya untuk Admin dan Manager</div>
</x-role-check>

<x-role-check role="sales">
    <div>Hanya untuk Sales</div>
</x-role-check>
```

---

## Permission Rules

### Access Control Matrix

| Feature | Sales | Supervisor | Manager | Admin |
|---------|-------|-----------|---------|-------|
| Dashboard | ✅ (Personal) | ✅ (Team) | ✅ (All) | ✅ (Full) |
| View Sales | ✅ (Own) | ✅ (Own+Team) | ✅ (All) | ✅ (All) |
| Create Sale | ✅ | ✅ | ✅ | ✅ |
| Edit Sale | ✅ (Own, <24h) | ⚠️ (Limited) | ⚠️ (Limited) | ✅ |
| Delete Sale | ❌ | ❌ | ⚠️ | ✅ |
| View Customer | ✅ (Own) | ✅ (Own+Team) | ✅ (All) | ✅ (All) |
| Create Customer | ✅ | ✅ | ✅ | ✅ |
| Manage Products | ❌ | ❌ | ✅ | ✅ |
| Manage Supplier | ❌ | ❌ | ✅ | ✅ |
| Manage Prices | ❌ | ❌ | ✅ | ✅ |
| Manage Area | ❌ | ❌ | ✅ | ✅ |
| View Salesman | ❌ | ✅ (Team) | ✅ (All) | ✅ (All) |
| Manage Salesman | ❌ | ⚠️ | ✅ | ✅ |
| Manage Team | ❌ | ✅ | ✅ | ✅ |
| View Cash Flow | ❌ | ❌ | ✅ | ✅ |
| Manage User & Role | ❌ | ❌ | ❌ | ✅ |

Keterangan:
- ✅ = Penuh akses
- ⚠️ = Akses terbatas dengan kondisi
- ❌ = Tidak ada akses

---

## Database Fields

### User Model
Field `role` di tabel `users`:
```sql
ALTER TABLE users ADD COLUMN role VARCHAR(30) DEFAULT 'sales';
```

Nilai valid:
- `admin` - Administrator
- `manager` - Manager
- `supervisor` - Supervisor
- `sales` - Penjualan

---

## How to Use in Code

### In Controller
```php
use App\Helpers\RoleHelper;

public function index()
{
    if (!RoleHelper::canAccessMenu('product')) {
        abort(403, 'Unauthorized');
    }
    
    // Controller logic
}
```

### In Blade Template
```blade
@php
    use App\Helpers\RoleHelper;
@endphp

@if(RoleHelper::isAdmin())
    <div>Admin only content</div>
@endif

@if(RoleHelper::hasRole('manager', 'admin'))
    <div>Manager or Admin content</div>
@endif

<x-role-check roles="admin,manager">
    <button>Edit</button>
</x-role-check>
```

### In Routes
```php
// Admin only
Route::middleware(['role:admin'])->group(function () {
    Route::resource('user', UserController::class);
});

// Manager and Admin
Route::middleware(['role:manager,admin'])->group(function () {
    Route::resource('product', ProductController::class);
});
```

---

## Password & Login

### Default Test Accounts
Untuk testing, gunakan seed atau buat akun dengan role:

```sql
-- Admin
INSERT INTO users (name, email, password, role)
VALUES ('Admin User', 'admin@test.com', bcrypt('password'), 'admin');

-- Manager
INSERT INTO users (name, email, password, role)
VALUES ('Manager User', 'manager@test.com', bcrypt('password'), 'manager');

-- Supervisor
INSERT INTO users (name, email, password, role)
VALUES ('Supervisor User', 'supervisor@test.com', bcrypt('password'), 'supervisor');

-- Sales
INSERT INTO users (name, email, password, role)
VALUES ('Sales User', 'sales@test.com', bcrypt('password'), 'sales');
```

---

## File Structure

```
app/
├── Helpers/
│   └── RoleHelper.php                    ← Role helper functions
├── Http/
│   ├── Controllers/
│   │   └── DashboardController.php       ← Role-specific dashboard routing
│   └── Middleware/
│       └── RoleMiddleware.php            ← Role validation middleware
└── View/
    └── Components/
        └── RoleCheck.php                 ← Role check component

resources/views/
├── layouts/
│   └── sidebar.blade.php                 ← Role-aware sidebar menu
└── dashboards/
    ├── admin.blade.php                   ← Admin dashboard
    ├── manager.blade.php                 ← Manager dashboard
    ├── supervisor.blade.php              ← Supervisor dashboard
    └── sales.blade.php                   ← Sales dashboard
```

---

## Troubleshooting

### Menu tidak muncul?
1. Pastikan helper di-autoload di `composer.json`
2. Jalankan `composer dump-autoload`
3. Clear cache: `php artisan config:clear`

### Akses ditolak?
1. Cek role di database: `SELECT role FROM users WHERE id = X;`
2. Cek middleware di route
3. Pastikan user sudah ter-login

### Dashboard tidak muncul?
1. Pastikan role field di users table ada
2. Cek view file di `resources/views/dashboards/`
3. Jalankan `php artisan view:clear`

---

## Keamanan

### Best Practices
1. **Selalu gunakan `$this->authorize()` di controller**
   ```php
   $this->authorize('view', $product);
   ```

2. **Gunakan Policy untuk complex authorization**
   ```php
   // app/Policies/ProductPolicy.php
   public function update(User $user, Product $product): bool {
       return $user->role === 'manager' || $user->role === 'admin';
   }
   ```

3. **Proteksi route dengan middleware**
   ```php
   Route::middleware(['role:admin'])->group(function () {
       // admin routes
   });
   ```

4. **Log akses penting**
   ```php
   \Log::info('Admin accessed user delete', ['user_id' => $id]);
   ```

---

## Development Notes

- Semua dashboard view sudah siap di folder `resources/views/dashboards/`
- RoleHelper sudah di-autoload (jika tidak, tambah ke `composer.json`)
- Sidebar sudah dinamis berdasarkan role
- Role badge ditampilkan di profile card

---

Generated: {{ date('Y-m-d H:i:s') }}
Version: 1.0
