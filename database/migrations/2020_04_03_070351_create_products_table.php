<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'products',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('product_id');
                $table->string('title', 2000);
                $table->string('handle', 2000);
                $table->string('main_image', 2000)
                    ->nullable();
                $table->text('images')
                    ->nullable();
                $table->string('price', 2000);
                $table->integer('shop_id')
                    ->unsigned();
                $table->string('platform', 1000)
                    ->nullable();
                $table->string('link', 2000)
                    ->nullable();
                $table->string('vendor', 2000)
                    ->nullable();
                $table->string('body_html', 2000)
                    ->nullable();
                $table->string('tags', 2000)
                    ->nullable();
                $table->string('product_type', 2000)
                    ->nullable();
                $table->string('product_prices', 2000)
                    ->nullable();
                $table->string('compare_at_price', 2000)
                    ->nullable();
                $table->string('weights', 2000)
                    ->nullable();
                $table->string('inventory_quantity', 2000)
                    ->nullable();
                $table->text('variants_titles')
                    ->nullable();
                $table->foreign('shop_id')
                    ->references('id')
                    ->on('shops')
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
        Schema::dropIfExists('products');
    }
}
