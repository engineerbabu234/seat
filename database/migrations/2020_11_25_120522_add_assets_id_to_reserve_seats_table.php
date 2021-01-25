<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAssetsIdToReserveSeatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reserve_seats', function (Blueprint $table) {
            $table->integer('office_asset_id')->unsigned()->nullable()->after("office_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reserve_seats', function (Blueprint $table) {
            $table->dropColumn(['office_asset_id']);
        });
    }
}
