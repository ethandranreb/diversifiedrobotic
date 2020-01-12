<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts_meta', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('post_id');
            $table->integer('created_by_id');
            $table->dateTime('created_on');
            $table->integer('display_for_id');
            $table->dateTime('shared_on')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts_meta');
    }
}
