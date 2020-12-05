<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('image',256);
            $table->char('status',1)->default(1)->comment("1= Enable,2 = Disable");
            $table->char('is_sub_category',1)->default(1)->comment("1= No,2 = Yes");
            $table->char('category_type',1)->default(1)->comment("1= Grocery,2 = Meat");
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
        Schema::dropIfExists('category');
    }
}
