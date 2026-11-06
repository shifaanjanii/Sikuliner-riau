<?php

namespace Tests\Feature;

use App\Models\Kabupaten;
use App\Models\PengajuanToko;
use App\Models\Role;
use App\Models\Toko;
use App\Models\Ulasan;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Sprint5Test extends TestCase
{
    use RefreshDatabase;

    private function createAdminUser(): User
    {
        $this->seed(RoleSeeder::class);
        $role = Role::where('name', 'admin')->firstOrFail();
        $user = new User([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => 'secret123',
        ]);
        $user->role()->associate($role);
        $user->save();
        return $user;
    }

    public function test_user_can_submit_review_for_shop(): void
    {
        $reg = Kabupaten::create(['name' => 'Kota Pekanbaru']);
        $shop = Toko::create([
            'kabupaten_id' => $reg->id,
            'name' => 'Toko Oleh-Oleh Melayu',
            'alamat' => 'Jl. Tuanku Tambusai 20',
        ]);

        $response = $this->post('/toko/' . $shop->id . '/ulasan', [
            'rating' => 5,
            'nama_pengulas' => 'Siti Rahma',
            'komentar' => 'Kuenya sangat enak dan pelayanannya ramah sekali.',
        ]);

        $response->assertRedirect('/toko/' . $shop->id . '#ulasan');

        $this->assertDatabaseHas('ulasan', [
            'toko_id' => $shop->id,
            'nama_pengulas' => 'Siti Rahma',
            'rating' => 5,
        ]);

        $this->assertEquals(5.0, $shop->fresh()->averageRating());
        $this->assertEquals(1, $shop->fresh()->reviewsCount());
    }

    public function test_admin_can_view_reviews_and_moderate(): void
    {
        $admin = $this->createAdminUser();
        $reg = Kabupaten::create(['name' => 'Kota Pekanbaru']);
        $shop = Toko::create([
            'kabupaten_id' => $reg->id,
            'name' => 'Pondok Gurami',
            'alamat' => 'Jl. Riau 45',
        ]);

        $review = Ulasan::create([
            'toko_id' => $shop->id,
            'nama_pengulas' => 'Spammer',
            'rating' => 1,
            'komentar' => 'Iklan spam yang tidak pantas.',
        ]);

        $response = $this->actingAs($admin)->get('/admin/ulasan');
        $response->assertOk()->assertSee('Spammer');

        $deleteResponse = $this->actingAs($admin)->delete('/admin/ulasan/' . $review->id);
        $deleteResponse->assertRedirect('/admin/ulasan');

        $this->assertDatabaseMissing('ulasan', [
            'id' => $review->id,
        ]);
    }

    public function test_admin_dashboard_shows_statistics_and_charts(): void
    {
        $admin = $this->createAdminUser();
        $reg = Kabupaten::create(['name' => 'Kota Pekanbaru']);
        Toko::create([
            'kabupaten_id' => $reg->id,
            'name' => 'Toko Uji',
            'alamat' => 'Jl. Uji 1',
        ]);
        PengajuanToko::create([
            'kabupaten_id' => $reg->id,
            'nama_toko' => 'Toko Mitra',
            'alamat' => 'Jl. Mitra 2',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertOk()
            ->assertSee('Dashboard Ringkasan SiKuliner Riau')
            ->assertSee('Sebaran Toko Kuliner per Kabupaten/Kota se-Riau')
            ->assertSee('shopsDistributionChart');
    }
}

