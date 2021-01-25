<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateApiFieldsApiConnectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('api_connections', function (Blueprint $table) {
            $table->tinyInteger('api_provider')->default(0);
            $table->text('api_description')->nullable();
            $table->renameColumn('api_name', 'api_title')->after('api_type');
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
            $table->dropColumn(['api_provider', 'api_description']);
            $table->renameColumn('api_title', 'api_name')->after('api_type');
        });
    }
}
