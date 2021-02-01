<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsInOfficeSeatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('office_seats', function (Blueprint $table) {
            $table->integer('building_id')->unsigned()->nullable()->after("office_id");
            $table->integer('office_asset_id')->unsigned()->nullable()->after("building_id");
            $table->integer('seat_id')->unsigned()->nullable()->after("office_asset_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('office_seats', function (Blueprint $table) {
            $table->dropColumn(['building_id', 'office_asset_id', 'seat_id']);
        });
    }
}
