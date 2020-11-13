<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewQuesionaireInOfficeAsset extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('office_asset', function (Blueprint $table) {
            $table->string('quesionaire_id')->nullable()->after("is_covid_test");
            $table->string('logic')->nullable()->after("quesionaire_id");
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
            $table->dropColumn(['quesionaire_id', 'logic']);
        });
    }
}
