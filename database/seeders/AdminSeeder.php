<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Rules\PasswordByteLength;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $data = config('sikuliner.admin');
        if (empty($data['email']) && empty($data['password']) && empty($data['name'])) {
            $this->command?->info('Admin belum dibuat. Jalankan php artisan sikuliner:admin.');

            return;
        }

        if (is_string($data['email'])) {
            $data['email'] = mb_strtolower(trim($data['email']));
        }
        Validator::make($data, [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'max:128', new PasswordByteLength, Password::min(8)->letters()->numbers()],
        ])->validate();

        $existing = User::where('email', $data['email'])->first();
        if ($existing) {
            if (! $existing->isAdmin()) {
                throw new \RuntimeException('Email admin dipakai pengguna biasa; akun tidak diubah.');
            }
            $this->command?->info('Admin sudah ada; password tidak diubah.');

            return;
        }

        $user = new User($data);
        $user->role()->associate(Role::where('name', 'admin')->firstOrFail());
        $user->save();
    }
}
