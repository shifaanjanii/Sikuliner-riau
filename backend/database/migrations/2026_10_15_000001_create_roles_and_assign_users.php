<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 30)->unique();
            $table->timestamps();
        });

        // Required reference data also makes migration safe for existing users.
        DB::table('roles')->insert(['name' => 'admin', 'created_at' => now(), 'updated_at' => now()]);
        $userRole = DB::table('roles')->insertGetId([
            'name' => 'pengguna', 'created_at' => now(), 'updated_at' => now(),
        ]);

        Schema::table('users', function (Blueprint $table) use ($userRole) {
            $table->foreignId('role_id')->default($userRole)->constrained('roles')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('role_id');
        });
        Schema::dropIfExists('roles');
    }
};
