<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'gabriel.martins.gsm19@gmail.com',
            'password' => bcrypt('wzT41T**x5PtXLkOVX(tKz1I'),
            'is_admin' => true,
        ]);
    }
}
