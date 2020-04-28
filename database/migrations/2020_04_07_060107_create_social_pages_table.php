<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocialPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'social_pages',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('social_id')
                    ->unsigned();
                $table->string('name');
                $table->string('provider_id');
                $table->text('access_token');
                $table->foreign('social_id')
                    ->references('id')
                    ->on('socials')
                    ->onDelete('cascade');
                $table->timestamps();
                $table->softDeletes();
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
        Schema::dropIfExists('social_pages');
    }
}
