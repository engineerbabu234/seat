<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCheckinoptionFieldInOfficeAssetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('office_asset', function (Blueprint $table) {
            $table->tinyInteger('checkin_method')->default(1);
            $table->tinyInteger('register_noshow')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('office_asset', function (Blueprint $table) {
            $table->dropColumn(['checkin_method']);
            $table->dropColumn(['register_noshow']);
        });
    }
}
