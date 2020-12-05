<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('delivery_boy_id');
            $table->double('amount',10,2)->default(0)->comment('Total Price After Discount');
            $table->double('shipping_charge',10,2)->default(0);
            $table->string('payment_request_id',256)->nullable();
            $table->string('payment_id',256)->nullable();
            $table->unsignedBigInteger('shipping_address_id')->nullable();
            $table->unsignedBigInteger('bulk_order_id')->nullable();
            $table->char('order_type',1)->default(1)->comment('1 = normal,2 = bulk');
            $table->char('delivery_type',1)->default(1)->comment('1 = normal,2 = express');
            $table->char('payment_type',1)->default(1)->comment('1 = cod,2 = online');
            $table->char('payment_status',1)->default(1)->comment('1 = cod,2 = paid,3 = failed');
            $table->char('delivery_status',1)->default(1)->comment('1 = pending,2 = accepted,3 = on the way,4 = delivered');
            $table->timestamp('assign_date')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
