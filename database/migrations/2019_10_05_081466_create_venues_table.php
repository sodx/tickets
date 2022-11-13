<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVenuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('venues', function (Blueprint $table) {
            $table->increments('venue_id');
            $table->string('name', 256)->nullable();
            $table->string('ticketmaster_id', 256)->nullable();
            $table->string('locale', 8)->nullable();
            $table->string('postcode', 10)->nullable();
            $table->string('timezone', 256)->nullable();
            $table->string('city', 256)->nullable();
            $table->string('country', 256)->nullable();
            $table->string('country_code', 256)->nullable();
            $table->string('state', 256)->nullable();
            $table->string('state_code', 256)->nullable();
            $table->string('address', 256)->nullable();
            $table->string('longtitude', 256)->nullable();
            $table->string('latitude', 256)->nullable();
            $table->string('image', 256)->nullable();
            $table->timestamps();
            $table->string('slug', 520)->nullable()->unique();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('venues');
    }
}
