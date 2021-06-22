<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('parent_id', false, true)->nullable()->index();
            $table->integer('lft', false, true)->nullable()->index();
            $table->integer('rgt', false, true)->nullable()->index();
            $table->integer('depth', false, true)->nullable();
            $table->string('title');
            $table->string('title_alias');
            $table->string('image')->nullable()->default(null);
            $table->text('summary');
            $table->string('component');
            $table->longText('params');
            $table->tinyInteger('public', false, true)->default(1);
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
        Schema::drop('categories');
    }
}
