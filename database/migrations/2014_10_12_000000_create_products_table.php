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
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',256);
            $table->string('category_id')->nullable();
            $table->string('sub_category_id')->nullable();
            $table->string('main_image',256)->nullable();
            $table->longText('description')->nullable();
            $table->double('min_price', 10, 2)->default(0);
            $table->double('mrp', 10, 2)->default(0);
            $table->bigInteger('stock')->default(0);
            $table->char('status',1)->default(1)->comment("1= Enable,2 = Disable");
            $table->char('product_type',1)->default(1)->comment("1= Grocery,2 = Meat");
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
        Schema::dropIfExists('products');
    }
}
