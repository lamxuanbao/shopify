<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocialPagePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'social_page_posts',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('social_page_id')
                    ->unsigned();
                $table->string('provider_id')
                    ->nullable();
                $table->string('message')
                    ->nullable();
                $table->foreign('social_page_id')
                    ->references('id')
                    ->on('social_pages')
                    ->onDelete('cascade');
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('social_page_posts');
    }
}
