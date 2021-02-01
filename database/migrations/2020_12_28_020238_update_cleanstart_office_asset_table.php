<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCleanstartOfficeAssetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('office_asset', function (Blueprint $table) {
            $table->renameColumn('checkin_time', 'checkin_start_time')->after('auto_realese');
            $table->renameColumn('checkout_time', 'checkout_start_time')->after('checkin_start_time');
            $table->time('checkin_end_time')->nullable();
            $table->time('checkout_end_time')->nullable();

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
            $table->renameColumn('checkin_start_time', 'checkin_time');
            $table->renameColumn('checkout_start_time', 'checkout_time');
            $table->dropColumn(['checkin_end_time', 'checkout_end_time']);
        });
    }
}
