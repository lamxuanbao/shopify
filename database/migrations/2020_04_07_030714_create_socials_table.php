<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'socials',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')
                    ->unsigned();
                $table->string('provider');
                $table->string('provider_id');
                $table->text('token');
                $table->text('refresh_token')
                    ->nullable();
                $table->text('expires_in')
                    ->nullable();
                $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
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
        Schema::dropIfExists('socials');
    }
}
