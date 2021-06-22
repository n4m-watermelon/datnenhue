<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentBlockDistrictTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_block_district', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('content_block_id', false, true);
            $table->integer('district_id', false, true);
            $table->timestamps();
            $table->foreign('content_block_id')->references('id')->on('content_blocks')
                ->onDelete('cascade');
            $table->foreign('district_id')->references('id')->on('districts')
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
        Schema::drop('content_block_district');
        Schema::table('content_block_district', function (Blueprint $table) {
            $table->dropForeign('content_block_district_content_block_id_foreign');
        });
        Schema::table('content_block_district', function (Blueprint $table) {
            $table->dropForeign('content_block_district_district_id_foreign');
        });
    }
}
