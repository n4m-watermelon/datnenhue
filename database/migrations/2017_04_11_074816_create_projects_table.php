<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('title_alias');
            $table->text('summary')->nullable()->default(null);
            $table->longText('content')->nullable()->default(null);
            $table->string('image');
            $table->longText('params');
            $table->tinyInteger('public', false, true)->default(1);
            $table->tinyInteger('featured', false, true)->default(0);
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
        Schema::drop('projects');
    }
}
