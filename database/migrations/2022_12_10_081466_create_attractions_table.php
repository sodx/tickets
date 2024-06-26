<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAttractionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('attractions');
        Schema::create('attractions', function (Blueprint $table) {
            $table->increments('attraction_id');
            $table->string('name', 256)->nullable();
            $table->string('ticketmaster_id', 256)->nullable();
            $table->string('locale', 8)->nullable();
            $table->string('type', 64)->nullable();
            $table->string('youtube_link', 128)->nullable();
            $table->string('twitter', 128)->nullable();
            $table->string('itunes', 128)->nullable();
            $table->string('lastfm', 128)->nullable();
            $table->string('wiki', 128)->nullable();
            $table->string('facebook', 128)->nullable();
            $table->string('homepage', 128)->nullable();
            $table->string('instagram', 128)->nullable();
            $table->string('thumbnail', 128)->nullable();
            $table->string('poster', 128)->nullable();
            $table->string('medium_image', 256)->nullable();
            $table->string('video_ids', 520)->nullable();
            $table->string('slug')->unique();
            $table->string('seo_title', 255)->nullable();
            $table->text('seo_description', 65535)->nullable();
            $table->text('seo_keywords', 65535)->nullable();
            $table->text('seo_content', 65535)->nullable();
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
        Schema::dropIfExists('attractions');
    }
}
