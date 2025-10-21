<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

public function up()
{
    Schema::table('parking_transactions', function (Blueprint $table) {
        $table->string('qr_code')->nullable()->after('plate');
    });
}

public function down()
{
    Schema::table('parking_transactions', function (Blueprint $table) {
        $table->dropColumn('qr_code');
    });
}
};
