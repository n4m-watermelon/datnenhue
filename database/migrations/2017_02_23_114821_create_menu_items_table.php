<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id', false, true)->nullable()->index();
            $table->integer('lft', false, true)->nullable()->index();
            $table->integer('rgt', false, true)->nullable()->index();
            $table->integer('depth', false, true)->nullable();
            $table->integer('group_id', false, true);
            $table->integer('type_id', false, true);
            $table->string('title');
            $table->integer('data_id', false, true)->nullable()->default(null);
            $table->string('link', 1024);
            $table->tinyInteger('public', false, true)->default(1);
            $table->integer('created_by', false, true)->nullable()->default(null);
            $table->integer('updated_by', false, true)->nullable()->default(null);
			$table->timestamps();
            $table->foreign('group_id')->references('id')->on('menu_groups')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropForeign('menu_items_group_id_foreign');
        });
        Schema::drop('menu_items');
    }
}
