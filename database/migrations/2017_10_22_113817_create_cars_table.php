<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->increments('id');
            $table->text('site');
            $table->integer('unique_car_id')->unique();
            $table->text('name');
            $table->text('link');
            $table->text('image_link');
            $table->text('engine_type')->nullable();
            $table->text('transmission')->nullable();
            $table->text('drive')->nullable();
            $table->text('mileage')->nullable();
            $table->text('city')->nullable();
            $table->text('price')->nullable();
            $table->boolean('on_sale');
            $table->boolean('notified')->default(false);
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
        Schema::dropIfExists('cars');
    }
}
