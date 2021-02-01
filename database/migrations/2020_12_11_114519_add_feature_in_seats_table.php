<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFeatureInSeatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seats', function (Blueprint $table) {
            $table->tinyInteger('monitor')->default(0);
            $table->tinyInteger('dokingstation')->default(0);
            $table->tinyInteger('adjustableheight')->default(0);
            $table->tinyInteger('privatespace')->default(0);
            $table->tinyInteger('wheelchair')->default(0);
            $table->tinyInteger('usbcharger')->default(0);
            $table->tinyInteger('privacy')->default(1);
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
            $table->dropColumn(['monitor']);
            $table->dropColumn(['dokingstation']);
            $table->dropColumn(['adjustableheight']);
            $table->dropColumn(['privatespace']);
            $table->dropColumn(['wheelchair']);
            $table->dropColumn(['usbcharger']);
            $table->dropColumn(['privacy']);
        });
    }
}
