<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boxes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('image');
            $table->tinyInteger('public', false, true)->default(1);
            $table->integer('min_price', false, true)->nullable()->default(0);
            $table->integer('max_price', false, true)->nullable()->default(0);
            $table->integer('min_acreage', false, true)->nullable()->default(0);
            $table->integer('max_acreage', false, true)->nullable()->default(0);
            $table->integer('created_by', false, true)->nullable()->default(null);
            $table->integer('updated_by', false, true)->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('boxes');
    }
}
