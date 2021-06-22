<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMetaSeosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meta_seos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('district_id',false);
            $table->unsignedInteger('category_id',false);
            $table->longText('params');
            $table->timestamps();
            $table->foreign('district_id')->references('id')->on('districts');
            $table->foreign('category_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meta_seos');
    }
}
