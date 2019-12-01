<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->bigIncrements('id');
            $table->string('title')->default('Not provided');
            $table->longText('description')->nullable();
            $table->unsignedInteger('ad_id')->default(0);
            $table->string('price')->default('Not provided');
            $table->unsignedInteger('year')->default(0);
            $table->string('mileage')->default('Not provided');
            $table->bigInteger('phone')->default(0);
            $table->string('link_to_website')->default('Not provided');
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
