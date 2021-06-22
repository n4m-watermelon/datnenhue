<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstateAreaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estate_area', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('estate_id',false,true);
            $table->integer('area_id',false,true);
            $table->nullableTimestamps();
            $table->foreign('estate_id')->references('id')->on('estates')->onDelete('cascade');;
            $table->foreign('area_id')->references('id')->on('areas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('estate_area');
    }
}
