-- ============================================================================
-- SINTARA - Database Queries
-- Bappeda Provinsi Lampung
-- ============================================================================

-- ============================================================================
-- 1. QUERY DATA ADMIN
-- ============================================================================
-- Menampilkan semua data pengguna dengan role ADMIN
SELECT 
    id,
    name AS 'Nama Lengkap',
    username AS 'Username',
    email AS 'Email',
    role AS 'Role',
    is_active AS 'Status Aktif',
    created_at AS 'Tanggal Dibuat',
    updated_at AS 'Tanggal Diupdate'
FROM users
WHERE role = 'admin';

-- ============================================================================
-- 2. QUERY DATA OPERATOR BIDANG SEKRETARIAT
-- ============================================================================
-- Menampilkan semua data pengguna dengan role OPERATOR di Bidang SEKRETARIAT
SELECT 
    u.id,
    u.name AS 'Nama Lengkap',
    u.username AS 'Username',
    u.email AS 'Email',
    b.nama_bidang AS 'Bidang',
    b.kode_bidang AS 'Kode Bidang',
    u.role AS 'Role',
    u.is_active AS 'Status Aktif',
    u.created_at AS 'Tanggal Dibuat'
FROM users u
JOIN bidang b ON u.bidang_id = b.id
WHERE u.role = 'operator' 
  AND b.kode_bidang = 'SEK';

-- ============================================================================
-- 3. QUERY SEMUA USER DENGAN BIDANGNYA
-- ============================================================================
-- Menampilkan semua pengguna beserta informasi bidangnya
SELECT 
    u.id,
    u.name AS 'Nama Lengkap',
    u.username AS 'Username',
    u.email AS 'Email',
    u.role AS 'Role',
    COALESCE(b.nama_bidang, 'N/A') AS 'Bidang',
    COALESCE(b.kode_bidang, '-') AS 'Kode Bidang',
    u.is_active AS 'Aktif',
    u.created_at AS 'Dibuat'
FROM users u
LEFT JOIN bidang b ON u.bidang_id = b.id
ORDER BY u.role DESC, u.name ASC;

-- ============================================================================
-- 4. QUERY DAFTAR BIDANG
-- ============================================================================
-- Menampilkan semua bidang/unit kerja
SELECT 
    id,
    nama_bidang AS 'Nama Bidang',
    kode_bidang AS 'Kode Bidang',
    created_at AS 'Dibuat',
    updated_at AS 'Diupdate'
FROM bidang
ORDER BY nama_bidang ASC;

-- ============================================================================
-- 5. QUERY JUMLAH USER PER BIDANG
-- ============================================================================
-- Menampilkan statistik jumlah user per bidang
SELECT 
    COALESCE(b.nama_bidang, 'Admin (No Bidang)') AS 'Bidang',
    COUNT(u.id) AS 'Jumlah User',
    SUM(CASE WHEN u.is_active = 1 THEN 1 ELSE 0 END) AS 'User Aktif',
    SUM(CASE WHEN u.is_active = 0 THEN 1 ELSE 0 END) AS 'User Nonaktif'
FROM users u
LEFT JOIN bidang b ON u.bidang_id = b.id
GROUP BY b.id, b.nama_bidang
ORDER BY b.nama_bidang ASC;

-- ============================================================================
-- 6. QUERY STATISTIK ROLE
-- ============================================================================
-- Menampilkan statistik pengguna berdasarkan role
SELECT 
    role AS 'Role',
    COUNT(*) AS 'Total User',
    SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) AS 'Aktif',
    SUM(CASE WHEN is_active = 0 THEN 1 ELSE 0 END) AS 'Nonaktif'
FROM users
GROUP BY role;

-- ============================================================================
-- 7. UPDATE STATUS ADMIN (Contoh)
-- ============================================================================
-- Update user admin untuk aktif
UPDATE users
SET is_active = 1, updated_at = NOW()
WHERE username = 'admin' AND role = 'admin';

-- ============================================================================
-- 8. UPDATE STATUS OPERATOR SEKRETARIAT (Contoh)
-- ============================================================================
-- Update user operator sekretariat untuk aktif
UPDATE users
SET is_active = 1, updated_at = NOW()
WHERE username = 'operator_sekretariat' AND role = 'operator';

-- ============================================================================
-- 9. INSERT ADMIN TAMBAHAN (Jika diperlukan)
-- ============================================================================
-- INSERT INTO users (name, username, email, password, role, bidang_id, is_active, created_at, updated_at)
-- VALUES ('Admin 2', 'admin2', 'admin2@sintara.test', 'hashed_password', 'admin', NULL, 1, NOW(), NOW());

-- ============================================================================
-- 10. INSERT OPERATOR SEKRETARIAT TAMBAHAN (Jika diperlukan)
-- ============================================================================
-- INSERT INTO users (name, username, email, password, role, bidang_id, is_active, created_at, updated_at)
-- SELECT 'Operator Sekretariat 2', 'operator_sekretariat2', 'operator_sekretariat2@sintara.test', 'hashed_password', 'operator', b.id, 1, NOW(), NOW()
-- FROM bidang b WHERE b.kode_bidang = 'SEK';
