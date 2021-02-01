<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiConnectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_connections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('api_type')->default(1);
            $table->string('api_name')->nullable();
            $table->string('api_key')->nullable();
            $table->string('api_secret')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('api_connections');
    }
}
