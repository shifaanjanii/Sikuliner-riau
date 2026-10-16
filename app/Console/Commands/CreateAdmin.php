<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use App\Rules\PasswordByteLength;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class CreateAdmin extends Command
{
    protected $signature = 'sikuliner:admin';

    protected $description = 'Buat admin melalui prompt lokal tanpa password di source code';

    public function handle(): int
    {
        $role = Role::where('name', 'admin')->first();
        if (! $role) {
            $this->error('Jalankan migration terlebih dahulu.');

            return self::FAILURE;
        }

        $data = [
            'name' => trim((string) $this->ask('Nama admin')),
            'email' => mb_strtolower(trim((string) $this->ask('Email admin'))),
            'password' => $this->secret('Password (minimal 8 karakter, huruf dan angka)'),
            'password_confirmation' => $this->secret('Ulangi password'),
        ];
        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'max:128', 'confirmed', new PasswordByteLength, Password::min(8)->letters()->numbers()],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $message) {
                $this->error($message);
            }

            return self::FAILURE;
        }

        $user = new User(collect($data)->only(['name', 'email', 'password'])->all());
        $user->role()->associate($role);
        $user->save();
        $this->info('Akun administrator berhasil dibuat.');

        return self::SUCCESS;
    }
}
