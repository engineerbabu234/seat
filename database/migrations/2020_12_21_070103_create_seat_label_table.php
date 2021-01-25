<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeatLabelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seat_label', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('building_id')->unsigned()->nullable();
            $table->integer('office_id')->unsigned()->nullable();
            $table->integer('office_asset_id')->unsigned()->nullable();
            $table->integer('seat_id')->unsigned()->nullable();
            $table->tinyInteger('scan')->default(1);
            $table->tinyInteger('nfc')->default(1);
            $table->date('label_order_date')->nullable();
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
        $table->dropColumn(['building_id', 'office_id', 'office_asset_id', 'seat_id', 'scan', 'nfc', 'label_order_date']);
    }
}
