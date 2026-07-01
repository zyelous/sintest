<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DisplayUserSeeder extends Seeder
{
    /**
     * Display existing admin and operator sekretariat users
     */
    public function run(): void
    {
        echo "\n" . str_repeat("=", 80) . "\n";
        echo "SINTARA - DATA PENGGUNA SISTEM\n";
        echo str_repeat("=", 80) . "\n\n";

        // Display Admin Users
        echo "📌 DATA ADMIN:\n";
        echo str_repeat("-", 80) . "\n";
        $admins = User::where('role', 'admin')->get();
        
        if ($admins->isEmpty()) {
            echo "❌ Tidak ada data admin\n";
        } else {
            foreach ($admins as $admin) {
                echo "ID: {$admin->id}\n";
                echo "Nama: {$admin->name}\n";
                echo "Username: {$admin->username}\n";
                echo "Email: {$admin->email}\n";
                echo "Role: {$admin->role}\n";
                echo "Status: " . ($admin->is_active ? '✓ Aktif' : '✗ Nonaktif') . "\n";
                echo "Dibuat: {$admin->created_at}\n";
                echo str_repeat("-", 80) . "\n";
            }
        }

        // Display Operator Sekretariat
        echo "\n📌 DATA OPERATOR BIDANG SEKRETARIAT:\n";
        echo str_repeat("-", 80) . "\n";
        $operators = User::where('role', 'operator')
            ->whereHas('bidang', function ($query) {
                $query->where('kode_bidang', 'SEK');
            })
            ->with('bidang')
            ->get();

        if ($operators->isEmpty()) {
            echo "❌ Tidak ada operator sekretariat\n";
        } else {
            foreach ($operators as $operator) {
                echo "ID: {$operator->id}\n";
                echo "Nama: {$operator->name}\n";
                echo "Username: {$operator->username}\n";
                echo "Email: {$operator->email}\n";
                echo "Bidang: {$operator->bidang->nama_bidang} ({$operator->bidang->kode_bidang})\n";
                echo "Role: {$operator->role}\n";
                echo "Status: " . ($operator->is_active ? '✓ Aktif' : '✗ Nonaktif') . "\n";
                echo "Dibuat: {$operator->created_at}\n";
                echo str_repeat("-", 80) . "\n";
            }
        }

        // Summary
        echo "\n📊 RINGKASAN:\n";
        echo str_repeat("-", 80) . "\n";
        $totalAdmin = User::where('role', 'admin')->count();
        $totalOperator = User::where('role', 'operator')->count();
        echo "Total Admin: {$totalAdmin}\n";
        echo "Total Operator: {$totalOperator}\n";
        echo "Total Pengguna: " . ($totalAdmin + $totalOperator) . "\n";
        echo str_repeat("=", 80) . "\n\n";
    }
}
