<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('block_team', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('team_id')->unsigned();
            $table->integer('building_id')->unsigned();
            $table->integer('office_id')->unsigned();
            $table->integer('seat_id')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('block_team');
    }
}
