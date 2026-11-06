<?php

namespace Tests\Feature;

use App\Models\Kabupaten;
use App\Models\KulinerKhas;
use App\Models\PengajuanToko;
use App\Models\Role;
use App\Models\Toko;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Sprint4Test extends TestCase
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

    public function test_public_can_view_promotion_form(): void
    {
        $reg = Kabupaten::create(['name' => 'Kota Pekanbaru']);

        $response = $this->get('/promosi');

        $response->assertOk()
            ->assertSee('Promosikan Toko Anda')
            ->assertSee('Kota Pekanbaru');
    }

    public function test_public_can_submit_promotion_request(): void
    {
        $reg = Kabupaten::create(['name' => 'Kabupaten Kampar']);

        $response = $this->post('/promosi', [
            'nama_toko' => 'Warung Patin Kampar',
            'nama_pemilik' => 'Pak Rusli',
            'nama_makanan' => 'Gulai Ikan Patin',
            'kabupaten_id' => $reg->id,
            'alamat' => 'Jl. Lintas Riau No. 10',
            'telepon' => '08123456789',
            'jam_buka' => '08.00 - 20.00 WIB',
            'maps_url' => 'https://maps.google.com/?q=Kampar',
            'latitude' => 0.3541200,
            'longitude' => 101.2789000,
        ]);

        $response->assertRedirect('/promosi')
            ->assertSessionHas('status');

        $this->assertDatabaseHas('pengajuan_toko', [
            'nama_toko' => 'Warung Patin Kampar',
            'status' => 'pending',
        ]);
    }

    public function test_admin_can_view_and_approve_promotion(): void
    {
        $admin = $this->createAdminUser();
        $reg = Kabupaten::create(['name' => 'Kota Pekanbaru']);

        $submission = PengajuanToko::create([
            'kabupaten_id' => $reg->id,
            'nama_toko' => 'Kedai Kopi Melayu',
            'alamat' => 'Jl. Senapelan No. 5',
            'telepon' => '0811223344',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->get('/admin/pengajuan');
        $response->assertOk()->assertSee('Kedai Kopi Melayu');

        $approveResponse = $this->actingAs($admin)->post('/admin/pengajuan/' . $submission->id . '/approve');
        $approveResponse->assertRedirect('/admin/pengajuan');

        $this->assertDatabaseHas('pengajuan_toko', [
            'id' => $submission->id,
            'status' => 'approved',
        ]);

        $this->assertDatabaseHas('toko', [
            'name' => 'Kedai Kopi Melayu',
            'kabupaten_id' => $reg->id,
        ]);
    }

    public function test_shop_detail_renders_map_and_navigation_button(): void
    {
        $reg = Kabupaten::create(['name' => 'Kota Pekanbaru']);
        $shop = Toko::create([
            'kabupaten_id' => $reg->id,
            'name' => 'Bolu Kemojo Rasa Al-Mahdi',
            'alamat' => 'Jl. Sudirman 100',
            'maps_url' => 'https://maps.google.com/?q=0.507,101.447',
            'latitude' => 0.5070670,
            'longitude' => 101.4477790,
        ]);

        $response = $this->get('/toko/' . $shop->id);

        $response->assertOk()
            ->assertSee('Buka Rute di Google Maps')
            ->assertSee('maps.google.com');
    }
}

