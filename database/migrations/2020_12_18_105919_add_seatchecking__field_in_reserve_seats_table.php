<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSeatcheckingFieldInReserveSeatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reserve_seats', function (Blueprint $table) {
            $table->tinyInteger('checkin_method')->default(0);
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
            $table->dropColumn(['checkin_method']);
        });
    }
}