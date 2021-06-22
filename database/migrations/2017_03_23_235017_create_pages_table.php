<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages',function ($table){
            $table->increments('id');
            $table->string('title');
            $table->string('title_alias');
            $table->longText('content');
            $table->string('image');
            $table->longText('params');
            $table->tinyInteger('public', false, true)->default(1);
            $table->integer('hits', false, true)->default(0);
            $table->integer('category_id', false, true);
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
        Schema::dropIfExists('pages');
    }
}
