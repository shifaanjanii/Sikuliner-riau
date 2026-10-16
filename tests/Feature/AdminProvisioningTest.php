<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Database\Seeders\AdminSeeder;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminProvisioningTest extends TestCase
{
    use RefreshDatabase;

    public function test_default_seed_does_not_create_an_account_with_known_password(): void
    {
        config(['sikuliner.admin' => ['name' => null, 'email' => null, 'password' => null]]);
        $this->seed(DatabaseSeeder::class);
        $this->seed(DatabaseSeeder::class);
        $this->assertDatabaseCount('roles', 2);
        $this->assertDatabaseCount('users', 0);
    }

    public function test_explicit_admin_seed_is_hashed_and_idempotent(): void
    {
        config(['sikuliner.admin' => ['name' => 'Admin Uji', 'email' => 'ADMIN@example.test', 'password' => 'RasaRiau123']]);
        $this->seed(AdminSeeder::class);
        $admin = User::firstOrFail();
        $hash = $admin->password;
        $this->assertTrue($admin->isAdmin());
        $this->assertTrue(Hash::check('RasaRiau123', $hash));
        $this->seed(AdminSeeder::class);
        $this->assertDatabaseCount('users', 1);
        $this->assertSame($hash, $admin->fresh()->password);
    }

    public function test_admin_seed_never_promotes_an_existing_public_account(): void
    {
        $user = User::factory()->create(['email' => 'pengguna@example.test']);
        config(['sikuliner.admin' => ['name' => 'Admin Uji', 'email' => $user->email, 'password' => 'RasaRiau123']]);
        try {
            $this->seed(AdminSeeder::class);
            $this->fail('Seeder seharusnya menolak email pengguna biasa.');
        } catch (\RuntimeException $exception) {
            $this->assertStringContainsString('akun tidak diubah', $exception->getMessage());
            $this->assertFalse($user->fresh()->isAdmin());
        }
    }

    public function test_command_creates_admin_using_hidden_password_prompt(): void
    {
        $this->artisan('sikuliner:admin')
            ->expectsQuestion('Nama admin', 'Admin Uji')
            ->expectsQuestion('Email admin', 'admin@example.test')
            ->expectsQuestion('Password (minimal 8 karakter, huruf dan angka)', 'RasaRiau123')
            ->expectsQuestion('Ulangi password', 'RasaRiau123')
            ->expectsOutput('Akun administrator berhasil dibuat.')
            ->assertSuccessful();
        $this->assertTrue(User::firstOrFail()->isAdmin());
    }

    public function test_role_cannot_be_mass_assigned(): void
    {
        $user = User::factory()->create();
        $user->fill(['role_id' => Role::where('name', 'admin')->value('id')])->save();
        $this->assertFalse($user->fresh()->isAdmin());
    }
}
