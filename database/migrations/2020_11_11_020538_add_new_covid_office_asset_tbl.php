<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewCovidOfficeAssetTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('office_asset', function (Blueprint $table) {
            $table->boolean('is_covid_test')->nullable()->after("asset_canvas");
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
            $table->dropColumn(['is_covid_test']);
        });
    }
}
