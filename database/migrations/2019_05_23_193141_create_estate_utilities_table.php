<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstateUtilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estate_utilities', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('estate_id',false,true);
            $table->integer('utility_id',false,true);
            $table->nullableTimestamps();
            $table->foreign('estate_id')->references('id')->on('estates')->onDelete('cascade');;
            $table->foreign('utility_id')->references('id')->on('utilities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('estate_utilities');
    }
}
