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

/**
 * Sprint 2 — Pengujian Direktori Toko
 *
 * Mencakup: CRUD toko admin, upload foto, validasi input,
 * pembatasan akses tamu/pengguna biasa, relasi kabupaten.
 */
class TokoTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $wisatawan;
    private Kabupaten $kampar;
    private Kabupaten $pekanbaru;
    private Toko $tokoKampar;
    private KulinerKhas $patin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);

        $adminRole = Role::where('name', 'admin')->firstOrFail();
        $userRole  = Role::where('name', 'pengguna')->firstOrFail();

        $this->admin = User::factory()->create();
        $this->admin->role()->associate($adminRole)->save();

        $this->wisatawan = User::factory()->create();
        $this->wisatawan->role()->associate($userRole)->save();

        $this->kampar = Kabupaten::create([
            'name'        => 'Kabupaten Kampar',
            'description' => 'Bumi Serambi Mekkah Riau',
        ]);

        $this->pekanbaru = Kabupaten::create([
            'name'        => 'Kota Pekanbaru',
            'description' => 'Ibu kota Provinsi Riau',
        ]);

        $this->patin = KulinerKhas::create([
            'kabupaten_id' => $this->kampar->id,
            'name'         => 'Gulai Ikan Patin',
            'category'     => 'Makanan Utama',
            'description'  => 'Gulai patin berempah kuning khas Kampar.',
        ]);

        $this->tokoKampar = Toko::create([
            'kabupaten_id' => $this->kampar->id,
            'name'         => 'Rumah Makan Patin Pak Itam',
            'alamat'       => 'Jl. Bangkinang–Pekanbaru Km 35',
            'telepon'      => '0812-7654-3210',
            'jam_buka'     => '10.00–22.00 WIB',
            'deskripsi'    => 'Spesialis ikan patin Sungai Kampar.',
            'latitude'     => 0.3337,
            'longitude'    => 101.0053,
        ]);
    }

    // ── Daftar toko admin ──────────────────────────────────────────────────────

    public function test_admin_can_view_toko_list(): void
    {
        $this->actingAs($this->admin)
            ->get('/admin/toko')
            ->assertOk()
            ->assertSee('Rumah Makan Patin Pak Itam')
            ->assertSee('Kabupaten Kampar');
    }

    public function test_guest_is_redirected_from_toko_list(): void
    {
        $this->get('/admin/toko')->assertRedirect('/login');
    }

    public function test_regular_user_is_forbidden_from_toko_admin(): void
    {
        $this->actingAs($this->wisatawan)->get('/admin/toko')->assertForbidden();
    }

    // ── Tambah toko ───────────────────────────────────────────────────────────

    public function test_admin_can_view_create_toko_form(): void
    {
        $this->actingAs($this->admin)
            ->get('/admin/toko/tambah')
            ->assertOk()
            ->assertSee('Tambah Toko');
    }

    public function test_admin_can_create_toko_with_valid_data(): void
    {
        $response = $this->actingAs($this->admin)
            ->post('/admin/toko', [
                'name'      => 'Kedai Mie Sagu Bu Halimah',
                'region_id' => $this->kampar->id,
                'alamat'    => 'Jl. Merdeka No. 12, Selatpanjang',
                'telepon'   => '0766-22113',
                'jam_buka'  => '06.00–14.00 WIB',
                'deskripsi' => 'Kedai sarapan mie sagu',
            ]);

        $response->assertRedirect('/admin/toko');
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('toko', [
            'name'   => 'Kedai Mie Sagu Bu Halimah',
            'alamat' => 'Jl. Merdeka No. 12, Selatpanjang',
        ]);
    }

    public function test_create_toko_requires_name_and_alamat(): void
    {
        $response = $this->actingAs($this->admin)
            ->post('/admin/toko', [
                'region_id' => $this->kampar->id,
                // name and alamat missing
            ]);

        $response->assertSessionHasErrors(['name', 'alamat']);
    }

    public function test_create_toko_requires_valid_region(): void
    {
        $response = $this->actingAs($this->admin)
            ->post('/admin/toko', [
                'name'      => 'Toko Uji',
                'region_id' => 9999, // non-existent
                'alamat'    => 'Jl. Test No. 1',
            ]);

        $response->assertSessionHasErrors(['region_id']);
    }

    public function test_create_toko_validates_maps_url_format(): void
    {
        $response = $this->actingAs($this->admin)
            ->post('/admin/toko', [
                'name'      => 'Toko Uji',
                'region_id' => $this->kampar->id,
                'alamat'    => 'Jl. Test No. 1',
                'maps_url'  => 'bukan-url-valid',
            ]);

        $response->assertSessionHasErrors(['maps_url']);
    }

    public function test_create_toko_validates_latitude_range(): void
    {
        $response = $this->actingAs($this->admin)
            ->post('/admin/toko', [
                'name'      => 'Toko Uji',
                'region_id' => $this->kampar->id,
                'alamat'    => 'Jl. Test No. 1',
                'latitude'  => '999', // out of range
            ]);

        $response->assertSessionHasErrors(['latitude']);
    }

    public function test_admin_can_create_toko_with_photo_upload(): void
    {
        $photo = UploadedFile::fake()->image('toko.jpg', 800, 600)->size(1024);

        $response = $this->actingAs($this->admin)
            ->post('/admin/toko', [
                'name'      => 'Toko Foto Uji',
                'region_id' => $this->kampar->id,
                'alamat'    => 'Jl. Foto No. 1',
                'photo'     => $photo,
            ]);

        $response->assertRedirect('/admin/toko');
        $response->assertSessionHas('status');

        $toko = Toko::where('name', 'Toko Foto Uji')->firstOrFail();
        $this->assertNotNull($toko->photo);
        $this->assertStringStartsWith('/uploads/shops/', $toko->photo);

        $filePath = public_path(ltrim($toko->photo, '/'));
        $this->assertFileExists($filePath);
        @unlink($filePath);
    }

    public function test_photo_upload_exceeding_2mb_is_rejected(): void
    {
        $largePhoto = UploadedFile::fake()->image('huge.jpg', 3000, 3000)->size(3000);

        $response = $this->actingAs($this->admin)
            ->post('/admin/toko', [
                'name'      => 'Toko Foto Besar',
                'region_id' => $this->kampar->id,
                'alamat'    => 'Jl. Besar No. 1',
                'photo'     => $largePhoto,
            ]);

        $response->assertSessionHasErrors('photo');
        $this->assertDatabaseMissing('toko', ['name' => 'Toko Foto Besar']);
    }

    // ── Edit / update toko ────────────────────────────────────────────────────

    public function test_admin_can_view_edit_toko_form(): void
    {
        $this->actingAs($this->admin)
            ->get('/admin/toko/' . $this->tokoKampar->id . '/edit')
            ->assertOk()
            ->assertSee('Rumah Makan Patin Pak Itam');
    }

    public function test_admin_can_update_toko(): void
    {
        $response = $this->actingAs($this->admin)
            ->put('/admin/toko/' . $this->tokoKampar->id, [
                'name'      => 'Rumah Makan Patin Pak Itam (Updated)',
                'region_id' => $this->kampar->id,
                'alamat'    => 'Jl. Bangkinang Km 40',
            ]);

        $response->assertRedirect('/admin/toko');
        $response->assertSessionHas('status');

        $this->assertSame(
            'Rumah Makan Patin Pak Itam (Updated)',
            $this->tokoKampar->fresh()->name
        );
        $this->assertSame('Jl. Bangkinang Km 40', $this->tokoKampar->fresh()->alamat);
    }

    // ── Hapus toko ────────────────────────────────────────────────────────────

    public function test_admin_can_delete_toko(): void
    {
        $tokoId = $this->tokoKampar->id;

        $this->actingAs($this->admin)
            ->delete('/admin/toko/' . $tokoId)
            ->assertRedirect('/admin/toko')
            ->assertSessionHas('status');

        $this->assertDatabaseMissing('toko', ['id' => $tokoId]);
    }

    // ── Koordinat dan lokasi ─────────────────────────────────────────────────

    public function test_toko_stores_coordinates_correctly(): void
    {
        $this->actingAs($this->admin)
            ->post('/admin/toko', [
                'name'      => 'Toko Koordinat',
                'region_id' => $this->pekanbaru->id,
                'alamat'    => 'Jl. Koordinat No. 1',
                'latitude'  => '0.5070677',
                'longitude' => '101.4478888',
                'maps_url'  => 'https://maps.google.com/?q=test',
            ])
            ->assertRedirect('/admin/toko');

        $toko = Toko::where('name', 'Toko Koordinat')->firstOrFail();
        $this->assertEqualsWithDelta(0.5070677, (float) $toko->latitude, 0.0001);
        $this->assertEqualsWithDelta(101.4478888, (float) $toko->longitude, 0.0001);
        $this->assertSame('https://maps.google.com/?q=test', $toko->maps_url);
    }

    // ── Relasi kuliner_toko ───────────────────────────────────────────────────

    public function test_toko_can_be_linked_to_kuliner_khas(): void
    {
        $this->tokoKampar->kulinerKhas()->attach($this->patin->id);

        $this->assertTrue($this->tokoKampar->kulinerKhas()->where('kuliner_khas_id', $this->patin->id)->exists());
        $this->assertSame('Gulai Ikan Patin', $this->tokoKampar->kulinerKhas->first()->name);
    }

    public function test_deleting_toko_removes_kuliner_pivot(): void
    {
        $this->tokoKampar->kulinerKhas()->attach($this->patin->id);

        $tokoId = $this->tokoKampar->id;
        $this->tokoKampar->delete();

        $this->assertDatabaseMissing('kuliner_toko', ['toko_id' => $tokoId]);
    }

    // ── Keamanan ──────────────────────────────────────────────────────────────

    public function test_regular_user_cannot_create_toko(): void
    {
        $this->actingAs($this->wisatawan)
            ->post('/admin/toko', [
                'name'      => 'Toko Ilegal',
                'region_id' => $this->kampar->id,
                'alamat'    => 'Jl. Ilegal No. 1',
            ])
            ->assertForbidden();

        $this->assertDatabaseMissing('toko', ['name' => 'Toko Ilegal']);
    }

    public function test_regular_user_cannot_delete_toko(): void
    {
        $this->actingAs($this->wisatawan)
            ->delete('/admin/toko/' . $this->tokoKampar->id)
            ->assertForbidden();

        $this->assertDatabaseHas('toko', ['id' => $this->tokoKampar->id]);
    }

    public function test_guest_cannot_access_toko_crud(): void
    {
        $this->get('/admin/toko')->assertRedirect('/login');
        $this->get('/admin/toko/tambah')->assertRedirect('/login');
        $this->post('/admin/toko', [])->assertRedirect('/login');
        $this->get('/admin/toko/' . $this->tokoKampar->id . '/edit')->assertRedirect('/login');
        $this->put('/admin/toko/' . $this->tokoKampar->id, [])->assertRedirect('/login');
        $this->delete('/admin/toko/' . $this->tokoKampar->id)->assertRedirect('/login');
    }
}
