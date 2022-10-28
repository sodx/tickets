<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blocks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('region_id')->nullable()->index('blocks_region_id_foreign');
            $table->boolean('status')->default(true);
            $table->unsignedInteger('order')->nullable();
            $table->string('title')->unique();
            $table->string('key')->unique();
            $table->text('content')->nullable();
            $table->string('images')->nullable();
            $table->text('urls')->nullable();
            $table->unsignedInteger('rules')->nullable();
            $table->text('details')->nullable();
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
        Schema::dropIfExists('blocks');
    }
}
