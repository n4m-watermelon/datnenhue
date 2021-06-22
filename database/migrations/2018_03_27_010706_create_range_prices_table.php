<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRangePricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('range_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->integer('min');
            $table->integer('max');
            $table->longText('params');
            $table->tinyInteger('public', false, true)->default(1);
            $table->integer('created_by', false, true)->nullable()->default(null);
            $table->integer('updated_by', false, true)->nullable()->default(null);
            $table->nullableTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('range_prices');
    }
}
