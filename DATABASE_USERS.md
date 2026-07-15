# SINTARA - Dokumentasi Database Queries
## Bappeda Provinsi Lampung

---

## 📋 DAFTAR PENGGUNA SISTEM

### Bagian ADMIN
Pengguna dengan akses penuh ke sistem

| Username | Password | Email | Nama |
|----------|----------|-------|------|
| admin | admin123 | admin@sintara.test | Administrator |

**Akses**: Semua fitur sistem tanpa batasan bidang

---

### Bagian OPERATOR BIDANG SEKRETARIAT
Pengguna operator untuk Bidang Sekretariat

| Username | Password | Email | Nama | Bidang |
|----------|----------|-------|------|--------|
| sekretariat | sekretariat123 | sekretariat@sintara.test | Operator SEKRETARIAT | SEKRETARIAT |

**Akses**: Fitur operator terbatas pada dokumen Bidang Sekretariat saja

---

### Bidang Lainnya (Referensi)
Pengguna operator untuk bidang lain yang tersedia

| Username | Password | Email | Bidang |
|----------|----------|-------|--------|
| ekonomi | ekonomi123 | ekonomi@sintara.test | EKONOMI |
| p3m | p3m123 | p3m@sintara.test | P3M |
| pik | pik123 | pik@sintara.test | PIK |
| pmpep | pmpep123 | pmpep@sintara.test | PMPEP |
| uptd | uptd123 | uptd@sintara.test | UPTD |

---

## 🔍 QUERY DATABASE

### 1. Query Data Admin
```sql
SELECT * FROM users WHERE role = 'admin';
```

### 2. Query Operator Sekretariat
```sql
SELECT u.*, b.nama_bidang 
FROM users u
JOIN bidang b ON u.bidang_id = b.id
WHERE u.role = 'operator' AND b.kode_bidang = 'SEK';
```

### 3. Query Semua User dengan Bidang
```sql
SELECT u.*, b.nama_bidang, b.kode_bidang
FROM users u
LEFT JOIN bidang b ON u.bidang_id = b.id
ORDER BY u.role DESC, u.name ASC;
```

### 4. Query Daftar Bidang
```sql
SELECT * FROM bidang ORDER BY nama_bidang ASC;
```

### 5. Query Jumlah User per Bidang
```sql
SELECT 
    COALESCE(b.nama_bidang, 'Admin') AS bidang,
    COUNT(u.id) AS total,
    SUM(u.is_active) AS aktif
FROM users u
LEFT JOIN bidang b ON u.bidang_id = b.id
GROUP BY b.id;
```

---

## 📝 STRUKTUR DATA

### Tabel `users`
- `id` - ID Pengguna (Primary Key)
- `name` - Nama Lengkap
- `username` - Username Login (Unique)
- `email` - Email (Unique)
- `password` - Password (Terenkripsi)
- `role` - Role: `admin` atau `operator`
- `bidang_id` - ID Bidang (Foreign Key ke tabel bidang)
- `is_active` - Status Aktif (1/0)
- `created_at` - Tanggal Dibuat
- `updated_at` - Tanggal Diupdate

### Tabel `bidang`
- `id` - ID Bidang (Primary Key)
- `nama_bidang` - Nama Bidang (100 karakter)
- `kode_bidang` - Kode Bidang (20 karakter)
- `created_at` - Tanggal Dibuat
- `updated_at` - Tanggal Diupdate

---

## 🔐 Cara Login

1. Akses aplikasi di `http://localhost:8000`
2. Masukkan Username
3. Masukkan Password
4. Klik Login

---

## ✅ Status Data
- ✓ Admin sudah terdaftar
- ✓ Operator Sekretariat sudah terdaftar
- ✓ Semua Bidang sudah terdaftar
- ✓ Database siap digunakan

Semua data user sudah aktif dan siap login ke sistem.
