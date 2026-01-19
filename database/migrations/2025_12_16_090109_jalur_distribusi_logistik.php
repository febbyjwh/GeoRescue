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
        Schema::create('logistiks', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lokasi');
            $table->string('district_id'); // contoh: 320405
            $table->string('village_id');  // contoh: 3204052001
            $table->enum('jenis_logistik', ['all(pangan, sandang, kesehatan, hunian)', 'pangan', 'sandang', 'kesehatan', 'hunian']); // Makanan, Obat, dll
            $table->integer('jumlah');
            $table->string('satuan'); // Paket, Box, Kg, dll
            $table->enum('status', ['Tersedia', 'Menipis', 'Habis'])->default('Tersedia');
            $table->decimal('lng', 10, 7);
            $table->decimal('lat', 10, 7);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        //
    }
};
