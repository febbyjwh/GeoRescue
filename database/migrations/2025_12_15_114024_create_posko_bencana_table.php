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
        Schema::create('posko_bencana', function (Blueprint $table) {
           $table->id();
            $table->string('nama_posko');
            $table->string('jenis_posko');
            $table->text('alamat_posko')->nullable();
            $table->string('nama_desa');
            $table->string('kecamatan');
            $table->decimal('latitude',10,7);
            $table->decimal('longitude',10,7);
            $table->enum('status_posko',['Aktif','Penuh','Tutup']);
            // $table->foreignId('bencana_id')
            //     ->constrained('bencana')
            //     ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posko_bencana');
    }
};
