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
        //admin
        User::firstOrCreate([
            'name' => 'admin',
            'email' => 'admin@mail.com',
        ],[
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'role' => 'admin',
        ]);

        $users = [
            ['name' => 'Cynthia', 'email' => 'Cynthiachineme@gmail.com'],
            ['name' => 'Hiruni', 'email' => 'hirunimalsha789@gmail.com' ],
            ['name' => 'Sampavi', 'email' => 'sampavi.shanthakumar@gmail.com'],
            ['name' => 'Vanessa', 'email' => 'kudos2vannycan@gmail.com'],
            ['name' => 'lava', 'email' => 'kirus.lava@gmail.com']
        ];

        foreach ($users as $user) {
            User::firstOrCreate([
                'name' => $user['name'],
                'email' => $user['email'],
            ], [
                'password' => bcrypt('secret'),
                'email_verified_at' => now(),
                'role' => 'admin',
            ]);
        }

        $this->command->info('Users table seeded!');
    }
}
