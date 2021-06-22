<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_blocks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('title_alias');
            $table->string('description');
            $table->tinyInteger('public', false, true)->default(0);
            $table->integer('type_id', false, true);
            $table->string('position')->nullable()->default(null);
            $table->integer('ordering', false, true)->nullable()->default(9999);
            $table->longText('params');
            $table->integer('created_by', false, true)->nullable()->default(null);
            $table->integer('updated_by', false, true)->nullable()->default(null);
            $table->foreign('type_id')->references('id')->on('content_block_types')
                ->onDelete('cascade');
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
        Schema::table('content_blocks', function (Blueprint $table) {
            $table->dropForeign('content_blocks_type_id_foreign');
        });
        Schema::drop('content_blocks');
    }
}
