<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_templates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('contract_id')->unsigned()->nullable();
            $table->integer('contract_document_id')->unsigned()->nullable();
            $table->string('contract_title')->nullable();
            $table->tinyInteger('contract_restrict_seat')->default(0);
            $table->text('contract_description')->nullable();
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
        Schema::dropIfExists('contract_templates');
    }
}
