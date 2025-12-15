<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bencanas', function (Blueprint $table) {
            $table->id();
            $table->string('Kecamatan');
            $table->string('Desa');
            $table->string('Jenis_Bencana');
            $table->string('Tingkat_Kerawanan');
            $table->json('geojson');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
