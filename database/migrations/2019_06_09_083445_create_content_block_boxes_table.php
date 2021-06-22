<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentBlockBoxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_block_boxes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('content_block_id', false, true);
            $table->integer('box_id', false, true);
            $table->timestamps();
            $table->foreign('content_block_id')->references('id')->on('content_blocks')
                ->onDelete('cascade');
            $table->foreign('box_id')->references('id')->on('boxes')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('content_block_boxes');
        Schema::table('content_block_boxes', function (Blueprint $table) {
            $table->dropForeign('content_block_boxes_content_block_id_foreign');
        });
        Schema::table('content_block_boxes', function (Blueprint $table) {
            $table->dropForeign('content_block_boxes_box_id_foreign');
        });
    }
}
