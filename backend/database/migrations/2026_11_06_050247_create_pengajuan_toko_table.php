<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_toko', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kabupaten_id')->constrained('kabupaten')->cascadeOnDelete();
            $table->string('nama_pemilik', 100)->nullable();
            $table->string('nama_toko', 150);
            $table->string('nama_makanan', 200)->nullable();
            $table->string('alamat', 255);
            $table->string('telepon', 30)->nullable();
            $table->string('jam_buka', 100)->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('photo', 255)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('maps_url', 500)->nullable();
            $table->text('catatan_admin')->nullable();
            $table->string('status', 20)->default('pending'); // pending, approved, rejected
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_toko');
    }
};
