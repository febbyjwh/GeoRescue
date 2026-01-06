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
        Schema::table('logistiks', function (Blueprint $table) {
            $table->decimal('lat', 10, 7)->nullable()->after('geojson');
            $table->decimal('lang', 10, 7)->nullable()->after('lat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logistiks', function (Blueprint $table) {
            $table->dropColumn(['lat', 'lang']);
        });
    }
};
