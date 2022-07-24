<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->integer('user_id')->unsigned();
            $table->string('order_id');
            $table->string('product_id');
            $table->string('txn_id')->nullable();
            $table->string('payment_mode')->nullable();
            $table->string('bank_txn_id')->nullable();
            $table->string('bank_name')->nullable();
            $table->integer('txn_amount');
            $table->longText('details')->nullable();
            $table->integer('status'); 
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
