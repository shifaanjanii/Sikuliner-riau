<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    private function registration(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Pengunjung Uji', 'email' => 'wisatawan@example.test',
            'password' => 'RasaRiau123', 'password_confirmation' => 'RasaRiau123',
        ], $overrides);
    }

    private function user(bool $admin = false): User
    {
        $user = User::factory()->make(['email' => 'akun@example.test', 'password' => 'RasaRiau123']);
        $user->role()->associate(Role::where('name', $admin ? 'admin' : 'pengguna')->firstOrFail());
        $user->save();

        return $user;
    }

    public function test_public_pages_render_with_real_auth_links(): void
    {
        $this->get('/')->assertOk()->assertSee(route('login'))->assertSee(route('register'));
        $this->get('/login')->assertOk()->assertSee('name="_token"', false);
        $this->get('/register')->assertOk()->assertSee('password_confirmation');
    }

    public function test_registration_hashes_password_and_normalizes_email(): void
    {
        $this->post('/register', $this->registration(['email' => '  WISATAWAN@example.test  ']))
            ->assertRedirect('/akun')->assertSessionHasNoErrors();
        $user = User::where('email', 'wisatawan@example.test')->firstOrFail();
        $this->assertAuthenticatedAs($user);
        $this->assertSame('pengguna', $user->role->name);
        $this->assertTrue(Hash::check('RasaRiau123', $user->password));
        $this->assertNotSame('RasaRiau123', $user->password);
    }

    public function test_registration_cannot_assign_admin_role_from_browser(): void
    {
        $this->post('/register', $this->registration([
            'role_id' => Role::where('name', 'admin')->value('id'), 'role' => 'admin', 'is_admin' => true,
        ]))->assertRedirect('/akun');
        $this->assertFalse(User::firstOrFail()->isAdmin());
    }

    public function test_registration_rejects_duplicate_email_ignoring_case(): void
    {
        $this->user();
        $this->post('/register', $this->registration(['email' => 'AKUN@example.test']))
            ->assertSessionHasErrors('email');
        $this->assertDatabaseCount('users', 1);
        $this->assertGuest();
    }

    public function test_registration_rejects_invalid_input_without_creating_user(): void
    {
        $this->post('/register', $this->registration([
            'name' => '', 'email' => 'bukan-email', 'password' => 'abc', 'password_confirmation' => 'beda',
        ]))->assertSessionHasErrors(['name', 'email', 'password']);
        $this->assertDatabaseCount('users', 0);
    }

    public function test_registration_rejects_non_string_fields(): void
    {
        $this->post('/register', $this->registration(['name' => ['x'], 'email' => ['x']]))
            ->assertSessionHasErrors(['name', 'email']);
        $this->assertDatabaseCount('users', 0);
    }

    public function test_passwords_exceeding_bcrypt_byte_limit_are_rejected(): void
    {
        $password = str_repeat('a', 72).'1';
        $this->post('/register', $this->registration([
            'password' => $password, 'password_confirmation' => $password,
        ]))->assertSessionHasErrors('password');
        $this->assertDatabaseCount('users', 0);
    }

    public function test_user_login_redirects_to_account_and_changes_session_id(): void
    {
        $user = $this->user();
        $this->get('/login');
        $before = session()->getId();
        $this->post('/login', ['email' => 'AKUN@example.test', 'password' => 'RasaRiau123'])
            ->assertRedirect('/akun');
        $this->assertAuthenticatedAs($user);
        $this->assertNotSame($before, session()->getId());
    }

    public function test_admin_login_redirects_to_dashboard(): void
    {
        $user = $this->user(true);
        $this->post('/login', ['email' => $user->email, 'password' => 'RasaRiau123'])
            ->assertRedirect('/admin');
        $this->get('/admin')->assertOk()->assertSee('Kamu masuk sebagai administrator');
    }

    public function test_login_rejects_wrong_password_without_flashing_it(): void
    {
        $user = $this->user();
        $this->from('/login')->post('/login', ['email' => $user->email, 'password' => 'Salah123'])
            ->assertRedirect('/login')->assertSessionHasErrors('email')
            ->assertSessionMissing('_old_input.password');
        $this->assertGuest();
    }

    public function test_login_requires_valid_fields(): void
    {
        $this->post('/login', [])->assertSessionHasErrors(['email', 'password']);
        $this->post('/login', ['email' => ['x'], 'password' => ['x']])->assertSessionHasErrors(['email', 'password']);
        $this->assertGuest();
    }

    public function test_sixth_login_attempt_is_locked_even_with_correct_password(): void
    {
        $user = $this->user();
        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', ['email' => $user->email, 'password' => 'Salah123'])->assertSessionHasErrors('email');
        }
        $response = $this->post('/login', ['email' => $user->email, 'password' => 'RasaRiau123']);
        $response->assertSessionHasErrors('email');
        $this->assertStringContainsString('Terlalu banyak percobaan', session('errors')->first('email'));
        $this->assertGuest();
    }

    public function test_user_can_login_after_lockout_expires(): void
    {
        $user = $this->user();
        $key = 'login:'.hash('sha256', $user->email.'|127.0.0.1');
        RateLimiter::hit($key, 60);
        $this->travel(61)->seconds();
        $this->post('/login', ['email' => $user->email, 'password' => 'RasaRiau123'])->assertRedirect('/akun');
        $this->assertAuthenticatedAs($user);
    }

    public function test_guest_cannot_access_protected_pages(): void
    {
        $this->get('/admin')->assertRedirect('/login');
        $this->get('/akun')->assertRedirect('/login');
    }

    public function test_user_cannot_access_admin_even_with_direct_url(): void
    {
        $this->actingAs($this->user())->get('/admin')->assertForbidden();
    }

    public function test_admin_middleware_also_blocks_write_actions(): void
    {
        foreach (['post', 'patch', 'delete'] as $method) {
            Route::$method('/test-admin-write', fn () => response('Tidak boleh tercapai'))
                ->middleware(['web', 'auth', 'admin']);
        }
        $this->actingAs($this->user());
        foreach (['post', 'patch', 'delete'] as $method) {
            $this->$method('/test-admin-write')->assertForbidden();
        }
    }

    public function test_logged_in_users_are_redirected_away_from_guest_forms(): void
    {
        $this->actingAs($this->user())->get('/login')->assertRedirect('/akun');
        $this->get('/register')->assertRedirect('/akun');
    }

    public function test_logout_invalidates_session_and_protected_access(): void
    {
        $this->actingAs($this->user())->withSession(['private_marker' => 'hapus']);
        $this->post('/logout')->assertRedirect('/login')->assertSessionMissing('private_marker');
        $this->assertGuest();
        $this->get('/akun')->assertRedirect('/login');
        $this->get('/logout')->assertStatus(405);
    }

    public function test_user_supplied_name_is_escaped(): void
    {
        $user = $this->user();
        $user->update(['name' => '<script>alert(1)</script>']);
        $this->actingAs($user)->get('/akun')->assertOk()
            ->assertDontSee('<script>alert(1)</script>', false)
            ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false);
    }

    public function test_csrf_rejects_missing_token_with_real_verification_enabled(): void
    {
        // Override only Laravel's test bypass; run the production token check.
        $this->app->bind(ValidateCsrfToken::class, EnforcedCsrfForTest::class);
        $this->post('/register', $this->registration())->assertStatus(419);
        $this->post('/login', ['email' => 'x@example.test', 'password' => 'RasaRiau123'])->assertStatus(419);
        $this->actingAs($this->user())->post('/logout')->assertStatus(419);
    }

    public function test_csrf_accepts_matching_session_token(): void
    {
        $this->app->bind(ValidateCsrfToken::class, EnforcedCsrfForTest::class);
        $token = str_repeat('t', 40);
        $this->withSession(['_token' => $token])->post('/register', $this->registration(['_token' => $token]))
            ->assertRedirect('/akun');
    }
}

class EnforcedCsrfForTest extends ValidateCsrfToken
{
    protected function runningUnitTests(): bool
    {
        return false;
    }
}
