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
        Schema::create('bencana', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kecamatan_id');
            $table->foreignId('desa_id');
            $table->enum('jenis_bencana', ['banjir', 'gempa', 'longsor']);
            $table->enum('tingkat_kerawanan', ['rendah', 'sedang', 'tinggi']);
            $table->decimal('nilai', 8, 2)->comment('Nilai pengukuran sesuai jenis bencana');
            $table->enum('satuan', [
                'cm',      // banjir
                'm',       // banjir / longsor
                'km',      // gempa (kedalaman)
                'sr',      // gempa (magnitudo)
                'mmi',     // gempa (intensitas)
                'm3',      // longsor
                'ha'       // longsor
            ])->nullable();
            $table->enum('status', ['aktif', 'penanganan', 'selesai']);
            $table->decimal('lang', 11, 8)->nullable();
            $table->decimal('lat', 10, 8)->nullable();
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
