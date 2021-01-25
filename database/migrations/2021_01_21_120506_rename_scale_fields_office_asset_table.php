<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameScaleFieldsOfficeAssetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('office_asset', function (Blueprint $table) {
            $table->renameColumn('vertical_scale', 'verticle_scale_pixelsper_cm')->after('daily_cost');
            $table->renameColumn('horizontal_scale', 'horizontal_scale_pixelsper_cm')->after('verticle_scale_pixelsper_cm');
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
            $table->renameColumn('verticle_scale_pixelsper_cm', 'vertical_scale')->after('daily_cost');
            $table->renameColumn('horizontal_scale_pixelsper_cm', 'horizontal_scale')->after('vertical_scale');
        });
    }
}
