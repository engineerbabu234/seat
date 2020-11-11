<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdInAllTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buildings', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->nullable()->after("building_id");
        });

        Schema::table('offices', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->nullable()->after("building_id");
        });

        Schema::table('office_asset', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->nullable()->after("building_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buildings', function (Blueprint $table) {
            $table->dropColumn(['user_id']);
        });

        Schema::table('offices', function (Blueprint $table) {
            $table->dropColumn(['user_id']);
        });

        Schema::table('office_asset', function (Blueprint $table) {
            $table->dropColumn(['user_id']);
        });
    }
}
