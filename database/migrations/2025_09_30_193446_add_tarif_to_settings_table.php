<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('settings', function (Blueprint $table) {
        $table->integer('tarif_awal_mobil')->default(3000);
        $table->integer('tarif_perjam_mobil')->default(2000);
        $table->integer('tarif_awal_motor')->default(2000);
        $table->integer('tarif_perjam_motor')->default(1000);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('settings', function (Blueprint $table) {
        $table->dropColumn([
            'tarif_awal_mobil',
            'tarif_perjam_mobil',
            'tarif_awal_motor',
            'tarif_perjam_motor',
        ]);
    });
}
};
