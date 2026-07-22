<?php

namespace Database\Seeders;

use App\Models\Bidang;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin utama
        User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator',
                'email' => 'admin@sintara.test',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'bidang_id' => null,
                'is_active' => true,
            ]
        );

        $operators = [
            ['nama' => 'Operator EKONOMI', 'username' => 'ekonomi', 'email' => 'ekonomi@sintara.test', 'password' => 'ekonomi123', 'kode' => 'EKO'],
            ['nama' => 'Operator P3M', 'username' => 'p3m', 'email' => 'p3m@sintara.test', 'password' => 'p3m123', 'kode' => 'P3M'],
            ['nama' => 'Operator PIK', 'username' => 'pik', 'email' => 'pik@sintara.test', 'password' => 'pik123', 'kode' => 'PIK'],
            ['nama' => 'Operator PMPEP', 'username' => 'pmpep', 'email' => 'pmpep@sintara.test', 'password' => 'pmpep123', 'kode' => 'PMP'],
            ['nama' => 'Operator SEKRETARIAT', 'username' => 'sekretariat', 'email' => 'sekretariat@sintara.test', 'password' => 'sekretariat123', 'kode' => 'SEK'],
            ['nama' => 'Operator UPTD', 'username' => 'uptd', 'email' => 'uptd@sintara.test', 'password' => 'uptd123', 'kode' => 'UPT'],
        ];

        foreach ($operators as $op) {
            $bidang = Bidang::where('kode_bidang', $op['kode'])->first();
            if ($bidang) {
                User::firstOrCreate(
                    ['username' => $op['username']],
                    [
                        'name' => $op['nama'],
                        'email' => $op['email'],
                        'password' => Hash::make($op['password']),
                        'role' => 'operator',
                        'bidang_id' => $bidang->id,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}