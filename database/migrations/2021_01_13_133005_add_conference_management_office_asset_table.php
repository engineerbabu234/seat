<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddConferenceManagementOfficeAssetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('office_asset', function (Blueprint $table) {
            $table->tinyInteger('conference_management')->default(1);
            $table->tinyInteger('email_user_link')->default(0);
            $table->tinyInteger('conference_endpoint')->default(0);
            $table->string('teleconferance_name')->nullable();
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
            $table->dropColumn(['conference_management', 'email_user_link', 'conference_endpoint', 'teleconferance_name']);
        });
    }
}
