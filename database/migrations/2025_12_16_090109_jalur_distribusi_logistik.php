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
        Schema::create('jalur_distribusi_logistik', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jalur');
            $table->string('asal_logistik');
            $table->decimal('asal_latitude', 10, 7);
            $table->decimal('asal_longitude', 10, 7);
            $table->string('tujuan_distribusi');
            $table->decimal('tujuan_latitude', 10, 7);
            $table->decimal('tujuan_longitude', 10, 7);
            $table->enum('status_jalur', ['aktif', 'terhambat', 'ditutup']);
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
