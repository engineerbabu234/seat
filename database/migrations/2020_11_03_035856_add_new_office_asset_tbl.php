<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewOfficeAssetTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('office_asset', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('building_id')->unsigned()->nullable();
            $table->integer('office_id')->unsigned()->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('preview_image')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('building_id')
                ->references('building_id')
                ->on('buildings')
                ->onDelete('cascade');

            $table->foreign('office_id')
                ->references('office_id')
                ->on('offices')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('office_asset');
    }
}
