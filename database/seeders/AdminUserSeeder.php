<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'aseanapoladmin'],
            [
                'name'     => 'ASEANAPOL Admin',
                'email'    => 'admin@aseanapol.org',
                'password' => 'j4g4d4t4aseanapol',
                'is_admin' => true,
            ]
        );
    }
}
