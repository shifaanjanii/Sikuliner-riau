<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kabupaten', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->text('description')->nullable();
            $table->string('photo', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('kuliner_khas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kabupaten_id')->nullable()->constrained('kabupaten')->onDelete('restrict');
            $table->string('name', 150);
            $table->string('category', 50);
            $table->text('description');
            $table->string('photo', 255)->nullable();
            $table->string('source', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kuliner_khas');
        Schema::dropIfExists('kabupaten');
    }
};
