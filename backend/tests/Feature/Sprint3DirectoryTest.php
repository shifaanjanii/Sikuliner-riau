<?php

namespace Tests\Feature;

use App\Models\Kabupaten;
use App\Models\KulinerKhas;
use App\Models\Role;
use App\Models\Toko;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Sprint3DirectoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_shops_are_only_listed_after_a_region_is_selected(): void
    {
        $kampar = Kabupaten::create(['name' => 'Kabupaten Kampar']);
        $pekanbaru = Kabupaten::create(['name' => 'Kota Pekanbaru']);
        $kamparShop = Toko::create(['kabupaten_id' => $kampar->id, 'name' => 'Rumah Makan Kampar', 'alamat' => 'Jl. Patin 1']);
        Toko::create(['kabupaten_id' => $pekanbaru->id, 'name' => 'Kedai Pekanbaru', 'alamat' => 'Jl. Sagu 2']);

        $this->get('/toko')
            ->assertOk()
            ->assertSee('Pilih lokasi dahulu')
            ->assertDontSee($kamparShop->name);

        $this->get('/toko?region=' . $kampar->id)
            ->assertOk()
            ->assertSee($kamparShop->name)
            ->assertDontSee('Kedai Pekanbaru');
    }

    public function test_shop_profile_shows_menu_prices_without_map_controls(): void
    {
        $kampar = Kabupaten::create(['name' => 'Kabupaten Kampar']);
        $food = KulinerKhas::create([
            'kabupaten_id' => $kampar->id,
            'name' => 'Gulai Ikan Patin',
            'category' => 'Makanan Utama',
            'description' => 'Kuliner khas Kampar.',
        ]);
        $shop = Toko::create([
            'kabupaten_id' => $kampar->id,
            'name' => 'Rumah Makan Patin',
            'alamat' => 'Jl. Bangkinang 35',
            'jam_buka' => '09.00 - 21.00 WIB',
            'maps_url' => 'https://maps.google.com/?q=kampar',
        ]);
        $shop->kulinerKhas()->attach($food->id, ['harga' => 35000]);

        $this->get('/toko/' . $shop->id)
            ->assertOk()
            ->assertSee('Jl. Bangkinang 35')
            ->assertSee('Rp 35.000')
            ->assertDontSee('Google Maps')
            ->assertDontSee('Navigasi Peta');
    }

    public function test_catalog_card_shows_story_location_and_connected_shops_without_a_detail_link(): void
    {
        $kampar = Kabupaten::create(['name' => 'Kabupaten Kampar']);
        $food = KulinerKhas::create([
            'kabupaten_id' => $kampar->id,
            'name' => 'Asam Pedas Patin',
            'category' => 'Makanan Utama',
            'description' => 'Kuah asam pedas dengan ikan patin segar.',
        ]);
        $shop = Toko::create([
            'kabupaten_id' => $kampar->id,
            'name' => 'Kedai Patin Kampar',
            'alamat' => 'Jl. Sungai Kampar 9',
            'maps_url' => 'https://maps.google.com/?q=kampar',
        ]);
        $food->toko()->attach($shop->id, ['harga' => 42000]);

        $this->get('/kuliner')
            ->assertOk()
            ->assertSee('Kuah asam pedas dengan ikan patin segar.')
            ->assertSee('Buka lokasi Kabupaten Kampar di Google Maps')
            ->assertSee('Kedai Patin Kampar')
            ->assertSee('Rp 42.000')
            ->assertDontSee('Baca Selengkapnya');
    }

    public function test_admin_can_save_menu_prices_for_a_shop(): void
    {
        $this->seed(RoleSeeder::class);
        $admin = User::factory()->create();
        $admin->role()->associate(Role::where('name', 'admin')->firstOrFail())->save();
        $kampar = Kabupaten::create(['name' => 'Kabupaten Kampar']);
        $food = KulinerKhas::create([
            'kabupaten_id' => $kampar->id,
            'name' => 'Bolu Kemojo',
            'category' => 'Kue',
            'description' => 'Kue khas Riau.',
        ]);

        $this->actingAs($admin)->post('/admin/toko', [
            'name' => 'Kedai Kemojo',
            'region_id' => $kampar->id,
            'alamat' => 'Jl. Kemojo 8',
            'menu' => [
                $food->id => ['food_id' => $food->id, 'selected' => '1', 'price' => '25000'],
            ],
        ])->assertRedirect('/admin/toko');

        $shop = Toko::where('name', 'Kedai Kemojo')->firstOrFail();
        $this->assertDatabaseHas('kuliner_toko', [
            'toko_id' => $shop->id,
            'kuliner_khas_id' => $food->id,
            'harga' => 25000,
        ]);
    }
}
