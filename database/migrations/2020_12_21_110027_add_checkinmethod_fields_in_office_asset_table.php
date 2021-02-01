<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCheckinmethodFieldsInOfficeAssetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('office_asset', function (Blueprint $table) {
            $table->tinyInteger('nfc')->default(1);
            $table->tinyInteger('qr')->default(1);
            $table->tinyInteger('browser')->default(1);
            $table->tinyInteger('token')->default(1);
            $table->tinyInteger('presence')->default(1);
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
            $table->dropColumn(['nfc', 'qr', 'browser', 'token', 'presence']);
        });
    }
}
