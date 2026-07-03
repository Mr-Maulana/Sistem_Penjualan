# TODO - Docker + Web Cache + Percepat Aplikasi (tanpa kurangi animasi/transisi)

## Rencana Perubahan
- [x] 1) Ubah Nginx config untuk header cache static asset + maintain gzip/gzip_static
- [x] 2) Ubah config/cache.php: default cache store -> redis

- [x] 3) Jalankan optimasi Laravel: config:cache, route:cache, view:cache, optimize:clear

- [x] 4) Pastikan build Vite production sudah siap (tanpa ubah animasi/transisi)

- [x] 5) Verifikasi aplikasi: halaman tetap sama (animasi/transisi tidak berubah)

