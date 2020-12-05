<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_setting', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('image',256);
            $table->char('status',1)->default(1)->comment("1= Enable,2 = Disable");
            $table->char('slider_type',1)->default(1)->comment("1= slider1,2 = Slider2");
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
        Schema::dropIfExists('app_setting');
    }
}
