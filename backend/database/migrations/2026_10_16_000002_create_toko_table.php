<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('toko', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kabupaten_id')->constrained('kabupaten')->cascadeOnDelete();
            $table->string('name', 150);
            $table->string('alamat', 255);
            $table->string('telepon', 30)->nullable();
            $table->string('jam_buka', 100)->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('photo', 255)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('maps_url', 500)->nullable();
            $table->timestamps();
        });

        // Pivot: kuliner yang dijual di setiap toko
        Schema::create('kuliner_toko', function (Blueprint $table) {
            $table->foreignId('toko_id')->constrained('toko')->cascadeOnDelete();
            $table->foreignId('kuliner_khas_id')->constrained('kuliner_khas')->cascadeOnDelete();
            $table->decimal('harga', 12, 0)->nullable()->comment('Harga dalam rupiah, NULL = belum diketahui');
            $table->primary(['toko_id', 'kuliner_khas_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kuliner_toko');
        Schema::dropIfExists('toko');
    }
};
