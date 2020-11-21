<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsOfficeAssetTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('office_asset', function (Blueprint $table) {
            $table->text('asset_canvas')->nullable()->after("preview_image");
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
            $table->dropColumn(['asset_canvas']);
        });
    }
}
