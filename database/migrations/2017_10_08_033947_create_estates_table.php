<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('lat')->nullable()->default(null);
            $table->string('lng')->nullable()->default(null);
            $table->date('start_date');
            $table->date('end_date');
            $table->string('title');
            $table->string('title_alias');
            $table->longText('content');
            $table->string('image')->nullable()->default(null);
            $table->integer('category_id',false,true);
            $table->tinyInteger('type_id',false,true);
            $table->integer('province_id',false,true);
            $table->integer('district_id',false,true);
            $table->integer('ward_id',false,true)->nullable()->default(null);
            $table->integer('street_id',false,true)->nullable()->default(null);
            $table->integer('project_id',false,true)->nullable()->default(null);
            $table->string('address')->nullable()->default(null);
            $table->decimal('price', 12, 0)->nullable()->default(null);
            $table->integer('unit_id',false,true)->nullable()->default(null);
            $table->string('area')->nullable()->default(null);
            $table->string('width')->nullable()->default(null);
            $table->string('land_width')->nullable()->default(null);
            $table->integer('home_direction',false,true)->nullable()->default(null);
            $table->integer('bacon_direction',false,true)->nullable()->default(null);
            $table->integer('floor_numbers',false,true)->nullable()->default(null);
            $table->integer('room_numbers',false,true)->nullable()->default(null);
            $table->integer('toilet_number',false,true)->nullable()->default(null);
            $table->string('service_price')->nullable()->default(null);
            $table->string('vat_price')->nullable()->default(null);
            $table->string('parking_fee')->nullable()->default(null);
            $table->string('electric_bill')->nullable()->default(null);
            $table->string('time_for_rent')->nullable()->default(null);
            $table->string('time_payment')->nullable()->default(null);
            $table->string('time_decor')->nullable()->default(null);
            $table->text('interior')->nullable()->default(null);
            $table->integer('approval',false,true);
            $table->integer('hits', false, true)->default(0);
            $table->tinyInteger('public', false, true)->default(1);
            $table->longText('params');
            $table->integer('approved_by', false, true)->nullable()->default(null);
            $table->dateTime('approved_at')->nullable()->default(null);
            $table->integer('created_by', false, true)->nullable()->default(null);
            $table->integer('updated_by', false, true)->nullable()->default(null);
            $table->softDeletes();
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
        Schema::dropIfExists('estates');
    }
}
