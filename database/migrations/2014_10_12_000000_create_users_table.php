<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('mobile')->unique();
            $table->date('dob')->nullable();
            $table->char('gender',1)->nullable()->comment("M=Male,F=Female");
            $table->string('api_token',256)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('state',256)->nullable();
            $table->string('city',256)->nullable();
            $table->string('address',256)->nullable();
            $table->string('pin',20)->nullable();
            $table->string('password');
            $table->char('status',1)->default(1)->comment("1= Enable,2 = Disable");
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
