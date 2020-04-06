<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'shops',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('shop_id');
                $table->string('access_token')
                    ->nullable();
                $table->string('name')
                    ->nullable();
                $table->string('email')
                    ->nullable();
                $table->string('domain')
                    ->nullable();
                $table->string('country_code')
                    ->nullable();
                $table->string('currency')
                    ->nullable();
                $table->string('iana_timezone')
                    ->nullable();
                $table->string('country')
                    ->nullable();
                $table->string('phone', 50)
                    ->nullable();
                $table->string('shop_owner', 400)
                    ->nullable();
                $table->string('money_format', 250)
                    ->nullable();
                $table->string('money_with_currency_format', 250)
                    ->nullable();
                $table->string('weight_unit', 20)
                    ->nullable();
                $table->string('plan_name', 100)
                    ->nullable();
                $table->boolean('password_enabled')
                    ->nullable();
                $table->boolean('has_storefront')
                    ->nullable();
                $table->boolean('force_ssl')
                    ->nullable();

                $table->integer('user_id')
                    ->nullable()
                    ->unsigned();
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
        Schema::dropIfExists('shops');
    }
}
