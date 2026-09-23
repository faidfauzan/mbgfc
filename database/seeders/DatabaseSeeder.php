<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
{
    // Akun Captain Utama
    User::create([
        'name' => 'FaidFazzn',
        'email' => 'superadmin@gmail.com',
        'password' => bcrypt('superadmin'),
        'role' => 'captain',
    ]);

    User::create([
        'name' => 'Rujian Khairi',
        'email' => 'rujiankhairi@gmail.com',
        'password' => bcrypt('rujiankhairi'),
        'role' => 'captain',
    ]);
}
