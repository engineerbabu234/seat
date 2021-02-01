<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameCodeSeatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seats', function (Blueprint $table) {
            $table->dropColumn(['qr_id', 'nfc_id']);

            $table->string('qr_code')->nullable();
            $table->string('nfc_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seats', function (Blueprint $table) {
            $table->string('qr_id')->nullable();
            $table->string('nfc_id')->nullable();

            $table->dropColumn(['qr_code', 'nfc_code']);
        });
    }
}
