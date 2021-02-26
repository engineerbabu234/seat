<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUsernamePasswordForTemplateApiConnectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('api_connections', function (Blueprint $table) {
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->string('integrator_key')->nullable();
            $table->string('host')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('api_connections', function (Blueprint $table) {
            $table->dropColumn(['username']);
            $table->dropColumn(['password']);
            $table->dropColumn(['integrator_key']);
            $table->dropColumn(['host']);
        });
    }
}
