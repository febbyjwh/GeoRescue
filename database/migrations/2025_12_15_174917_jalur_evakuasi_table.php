<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('jalur_evakuasis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jalur');          // Nama jalur
            $table->text('deskripsi')->nullable(); // Deskripsi jalur
            $table->json('geojson');         // GeoJSON untuk garis jalur evakuasi
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();           // created_at & updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('jalur_evakuasis');
    }
};
