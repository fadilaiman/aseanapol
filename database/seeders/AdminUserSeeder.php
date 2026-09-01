<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $existing = User::where('username', 'aseanapoladmin')->first();

        if ($existing) {
            // Never overwrite an existing admin's password on re-seed.
            return;
        }

        $password = env('ADMIN_SEED_PASSWORD') ?: Str::password(24);

        User::create([
            'name'     => 'ASEANAPOL Admin',
            'username' => 'aseanapoladmin',
            'email'    => 'admin@aseanapol.org',
            'password' => $password,
            'is_admin' => true,
        ]);

        if (! env('ADMIN_SEED_PASSWORD')) {
            $this->command?->warn("Generated admin password (save this now, it will not be shown again): {$password}");
        }
    }
}
