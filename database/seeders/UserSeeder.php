<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Super Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@smkn5tpi.sch.id'],
            [
                'name' => 'Admin SIPENA',
                'nip' => '0000000000',
                'password' => Hash::make('password'),
                'active' => true,
            ]
        );
        $admin->assignRole('super_admin');

        // Create Headmaster
        $headmaster = User::firstOrCreate(
            ['email' => 'kepala@smkn5tpi.sch.id'],
            [
                'name' => 'Kepala Sekolah',
                'nip' => '1234567890',
                'password' => Hash::make('password'),
                'active' => true,
            ]
        );
        $headmaster->assignRole('kepala_sekolah');

        // Create Sample Teachers
        for ($i = 1; $i <= 10; $i++) {
            $teacher = User::firstOrCreate(
                ['email' => "guru{$i}@smkn5tpi.sch.id"],
                [
                    'name' => "Guru {$i}",
                    'nip' => str_pad($i, 10, '0', STR_PAD_LEFT),
                    'password' => Hash::make('password'),
                    'active' => true,
                ]
            );
            $teacher->assignRole('guru');
        }
    }
}
