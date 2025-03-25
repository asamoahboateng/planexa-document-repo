<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::firstOrCreate([
            'name' => 'admin',
            'email' => 'admin@mail.com',
        ],[
            'password' => bcrypt('secret'),
            'email_verified_at' => now(),
            'role' => 'admin',
        ]);
    }
}
