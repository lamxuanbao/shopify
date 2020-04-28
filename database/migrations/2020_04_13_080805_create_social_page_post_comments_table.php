<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocialPagePostCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'social_page_post_comments',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('social_page_post_id')
                    ->unsigned();
                $table->string('provider_id')
                    ->nullable();
                $table->string('message')
                    ->nullable();
                $table->foreign('social_page_post_id')
                    ->references('id')
                    ->on('social_page_posts')
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
        Schema::dropIfExists('social_page_post_comments');
    }
}
