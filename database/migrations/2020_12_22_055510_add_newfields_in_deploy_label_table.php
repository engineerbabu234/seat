<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewfieldsInDeployLabelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deploy_label', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('seat_label_id')->unsigned()->nullable();
            $table->integer('building_id')->unsigned()->nullable();
            $table->integer('office_id')->unsigned()->nullable();
            $table->integer('office_asset_id')->unsigned()->nullable();
            $table->integer('seat_id')->unsigned()->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('deploy_label', function (Blueprint $table) {
            $table->dropColumn(['seat_label_id', 'building_id', 'office_id', 'office_asset_id', 'seat_id', 'status']);
        });
    }
}
