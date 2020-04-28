<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocialPageConversationDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'social_page_conversation_details',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('social_page_conversation_id')
                    ->unsigned();
                $table->string('provider_id')
                    ->nullable();
                $table->text('message')
                    ->nullable();
                $table->text('sticker')
                    ->nullable();
                $table->text('attachments')
                    ->nullable();
                $table->text('from')
                    ->nullable();
                $table->foreign('social_page_conversation_id')
                    ->references('id')
                    ->on('social_page_conversations')
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
        Schema::dropIfExists('social_page_conversation_details');
    }
}
