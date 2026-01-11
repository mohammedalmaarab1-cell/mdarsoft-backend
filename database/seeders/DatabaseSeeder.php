<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SettingSeeder::class,
        ]);

        if (!User::where('email', 'admin@madarsoft.com')->exists()) {
            User::create([
                'name' => 'Admin User',
                'email' => 'admin@madarsoft.com',
                'password' => Hash::make('password'),
            ]);
        }
    }
}
