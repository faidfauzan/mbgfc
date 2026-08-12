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
        'name' => 'Faid Fauzan',
        'email' => 'faidfrch@gmail.com',
        'password' => bcrypt('12345678'),
        'role' => 'captain',
    ]);

    // Data Dummy Tambahan (10 Member)
    User::factory(10)->create();
}
}
