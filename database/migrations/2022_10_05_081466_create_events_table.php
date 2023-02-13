<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('event_id');
            $table->string('name', 256)->nullable();
            $table->string('ticketmaster_id', 256)->nullable();
            $table->string('url', 256)->nullable();
            $table->string('seatmap', 256)->nullable();
            $table->text('info', 65535)->nullable();
            $table->text('pleaseNote', 65535)->nullable();
            $table->string('thumbnail', 256)->nullable();
            $table->string('poster', 256)->nullable();
            $table->string('medium_image', 256)->nullable();
            $table->text('images', 65535)->nullable();
            $table->integer('price_min')->nullable();
            $table->integer('price_max')->nullable();
            $table->string('price_currency', 64)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('status')->nullable();
            $table->string('meta_title', 256)->nullable();
            $table->string('meta_keywords', 512)->nullable();
            $table->text('meta_description', 65535)->nullable();
            $table->integer('views')->nullable();
            $table->integer('clicks')->nullable();
            $table->integer('segment_id')->nullable();
            $table->integer('genre_id')->nullable();
            $table->integer('subgenre_id')->nullable();
            $table->integer('venue_id')->nullable();
            $table->integer('tour_id')->nullable();
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
        Schema::dropIfExists('events');
    }
}
