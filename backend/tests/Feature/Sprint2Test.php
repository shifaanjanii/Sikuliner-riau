<?php

namespace Tests\Feature;

use App\Models\Kabupaten;
use App\Models\KulinerKhas;
use App\Models\Role;
use App\Models\Toko;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class Sprint2Test extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $wisatawan;
    private Kabupaten $kampar;
    private Kabupaten $pekanbaru;
    private KulinerKhas $patin;
    private KulinerKhas $kemojo;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);

        $adminRole = Role::where('name', 'admin')->firstOrFail();
        $userRole = Role::where('name', 'pengguna')->firstOrFail();

        $this->admin = User::factory()->create();
        $this->admin->role()->associate($adminRole)->save();

        $this->wisatawan = User::factory()->create();
        $this->wisatawan->role()->associate($userRole)->save();

        $this->kampar = Kabupaten::create([
            'name' => 'Kabupaten Kampar',
            'description' => 'Bumi Serambi Mekkah Riau',
            'photo' => null,
        ]);

        $this->pekanbaru = Kabupaten::create([
            'name' => 'Kota Pekanbaru',
            'description' => 'Ibu kota Provinsi Riau',
            'photo' => null,
        ]);

        $this->patin = KulinerKhas::create([
            'kabupaten_id' => $this->kampar->id,
            'name' => 'Gulai Ikan Patin',
            'category' => 'Makanan Utama',
            'description' => 'Gulai patin berempah kuning khas Kampar.',
            'photo' => 'images/gulai-patin.webp',
            'source' => null,
        ]);

        $this->kemojo = KulinerKhas::create([
            'kabupaten_id' => $this->pekanbaru->id,
            'name' => 'Bolu Kemojo',
            'category' => 'Kue Tradisional',
            'description' => 'Bolu pandan legit khas Pekanbaru.',
            'photo' => 'images/bolu-kemojo.webp',
            'source' => 'https://ppid.riau.go.id/berita/19506/bolu-kemojo',
        ]);
    }

    public function test_homepage_and_catalog_render_with_data(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Gulai Ikan Patin')
            ->assertSee('Bolu Kemojo')
            ->assertSee('Kabupaten Kampar')
            ->assertSee('Kota Pekanbaru');

        $this->get('/kuliner')
            ->assertOk()
            ->assertSee('Gulai Ikan Patin')
            ->assertSee('Bolu Kemojo');

        $this->get('/tentang')
            ->assertOk()
            ->assertSee('Tentang SiKuliner Riau')
            ->assertSee('Kabupaten Kampar')
            ->assertSee('Kota Pekanbaru');
    }

    public function test_catalog_filters_by_region(): void
    {
        $response = $this->get('/kuliner?region=' . $this->kampar->id);

        $response->assertOk();
        $response->assertSee('Gulai Ikan Patin');
        $response->assertDontSee('Bolu Kemojo');
    }

    public function test_catalog_searches_by_keyword(): void
    {
        $response = $this->get('/kuliner?q=Kemojo');

        $response->assertOk();
        $response->assertSee('Bolu Kemojo');
        $response->assertDontSee('Gulai Ikan Patin');
    }

    public function test_food_detail_page_renders_correctly(): void
    {
        $response = $this->get('/kuliner/' . $this->patin->id);

        $response->assertOk();
        $response->assertSee('Gulai Ikan Patin');
        $response->assertSee('Kabupaten Kampar');
        $response->assertSee('Makanan Utama');
        $response->assertSee('Gulai patin berempah kuning khas Kampar.');
    }

    public function test_food_detail_links_to_the_city_shop_directory(): void
    {
        $toko = Toko::create([
            'kabupaten_id' => $this->kampar->id,
            'name' => 'RM Pondok Patin Kampar',
            'alamat' => 'Jl. Lintas Riau No. 12',
            'telepon' => '081234567890',
            'jam_buka' => '08:00 - 21:00 WIB',
            'maps_url' => 'https://maps.google.com/?q=Kampar',
        ]);
        $this->patin->toko()->attach($toko->id, ['harga' => 45000]);

        $response = $this->get('/kuliner/' . $this->patin->id);

        $response->assertOk();
        $response->assertSee('Cari tempat mencicipinya');
        $response->assertSee('/toko?region=' . $this->kampar->id, false);
        $response->assertDontSee('RM Pondok Patin Kampar');
        $response->assertDontSee('https://maps.google.com/?q=Kampar');
    }

    public function test_admin_can_view_regions_and_foods_lists(): void
    {
        $this->actingAs($this->admin)
            ->get('/admin/wilayah')
            ->assertOk()
            ->assertSee('Kabupaten Kampar')
            ->assertSee('Kota Pekanbaru');

        $this->actingAs($this->admin)
            ->get('/admin/makanan')
            ->assertOk()
            ->assertSee('Gulai Ikan Patin')
            ->assertSee('Bolu Kemojo');
    }

    public function test_admin_can_create_kabupaten_and_validate(): void
    {
        $this->actingAs($this->admin)
            ->post('/admin/wilayah', [
                'name' => 'Kabupaten Siak',
                'description' => 'Negeri Istana Siak Sri Indrapura.',
            ])
            ->assertRedirect('/admin/wilayah')
            ->assertSessionHas('status');

        $this->assertDatabaseHas('kabupaten', [
            'name' => 'Kabupaten Siak',
        ]);
    }

    public function test_admin_can_update_kabupaten(): void
    {
        $this->actingAs($this->admin)
            ->put('/admin/wilayah/' . $this->kampar->id, [
                'name' => 'Kabupaten Kampar Updated',
                'description' => 'Deskripsi yang diperbarui.',
            ])
            ->assertRedirect('/admin/wilayah')
            ->assertSessionHas('status');

        $this->assertSame('Kabupaten Kampar Updated', $this->kampar->fresh()->name);
    }

    public function test_kabupaten_cannot_be_deleted_if_foods_are_attached(): void
    {
        $response = $this->actingAs($this->admin)
            ->delete('/admin/wilayah/' . $this->kampar->id);

        $response->assertRedirect('/admin/wilayah');
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('kabupaten', [
            'id' => $this->kampar->id,
        ]);
    }

    public function test_admin_can_delete_empty_kabupaten(): void
    {
        $emptyRegion = Kabupaten::create([
            'name' => 'Kabupaten Baru',
            'description' => 'Belum ada makanan',
        ]);

        $this->actingAs($this->admin)
            ->delete('/admin/wilayah/' . $emptyRegion->id)
            ->assertRedirect('/admin/wilayah')
            ->assertSessionHas('status');

        $this->assertDatabaseMissing('kabupaten', [
            'id' => $emptyRegion->id,
        ]);
    }

    public function test_admin_can_create_kuliner_with_valid_photo_upload(): void
    {
        $photo = UploadedFile::fake()->image('lopek.jpg', 600, 400)->size(1024); // 1 MB (under 2MB limit)

        $response = $this->actingAs($this->admin)
            ->post('/admin/makanan', [
                'name' => 'Lopek Bugi',
                'region_id' => $this->kampar->id,
                'category' => 'Kue Tradisional',
                'description' => 'Lepat ketan manis berbalut daun pisang.',
                'photo' => $photo,
            ]);

        $response->assertRedirect('/admin/makanan');
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('kuliner_khas', [
            'name' => 'Lopek Bugi',
            'category' => 'Kue Tradisional',
        ]);

        $createdFood = KulinerKhas::where('name', 'Lopek Bugi')->firstOrFail();
        $this->assertNotNull($createdFood->photo);
        $this->assertStringStartsWith('/uploads/foods/', $createdFood->photo);

        $filePath = public_path(ltrim($createdFood->photo, '/'));
        $this->assertFileExists($filePath);
        @unlink($filePath);
    }

    public function test_photo_upload_exceeding_2mb_is_rejected(): void
    {
        $largePhoto = UploadedFile::fake()->image('huge.jpg', 2000, 2000)->size(3000); // 3 MB (exceeds 2MB)

        $response = $this->actingAs($this->admin)
            ->post('/admin/makanan', [
                'name' => 'Makanan Besar',
                'region_id' => $this->kampar->id,
                'category' => 'Makanan Utama',
                'description' => 'Deskripsi makanan.',
                'photo' => $largePhoto,
            ]);

        $response->assertSessionHasErrors('photo');
        $this->assertDatabaseMissing('kuliner_khas', [
            'name' => 'Makanan Besar',
        ]);
    }

    public function test_admin_can_update_kuliner_khas(): void
    {
        $this->actingAs($this->admin)
            ->put('/admin/makanan/' . $this->patin->id, [
                'name' => 'Gulai Ikan Patin Kampar',
                'region_id' => $this->kampar->id,
                'category' => 'Makanan Utama',
                'description' => 'Deskripsi baru yang lebih lengkap.',
            ])
            ->assertRedirect('/admin/makanan')
            ->assertSessionHas('status');

        $this->assertSame('Gulai Ikan Patin Kampar', $this->patin->fresh()->name);
    }

    public function test_admin_can_delete_kuliner_khas(): void
    {
        $this->actingAs($this->admin)
            ->delete('/admin/makanan/' . $this->patin->id)
            ->assertRedirect('/admin/makanan')
            ->assertSessionHas('status');

        $this->assertDatabaseMissing('kuliner_khas', [
            'id' => $this->patin->id,
        ]);
    }

    public function test_guest_is_redirected_from_admin_crud(): void
    {
        $this->get('/admin/wilayah')->assertRedirect('/login');
        $this->get('/admin/makanan')->assertRedirect('/login');
        $this->post('/admin/wilayah', [])->assertRedirect('/login');
        $this->post('/admin/makanan', [])->assertRedirect('/login');
    }

    public function test_regular_user_is_forbidden_from_admin_crud(): void
    {
        $this->actingAs($this->wisatawan)->get('/admin/wilayah')->assertForbidden();
        $this->actingAs($this->wisatawan)->get('/admin/makanan')->assertForbidden();
        $this->actingAs($this->wisatawan)->post('/admin/wilayah', ['name' => 'Tes'])->assertForbidden();
        $this->actingAs($this->wisatawan)->post('/admin/makanan', ['name' => 'Tes'])->assertForbidden();
    }
}
