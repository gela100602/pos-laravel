<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id('transaction_id');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->decimal('total_items', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->unsignedBigInteger('discount_id')->nullable();
            $table->decimal('payment', 10, 2)->default(0);
            $table->decimal('received', 10, 2)->default(0);
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('customer_id')
                ->references('customer_id')
                ->on('customers')
                ->onUpdate('cascade');

            $table->foreign('discount_id')
                ->references('discount_id')
                ->on('discounts')
                ->onUpdate('cascade');
            
            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_transactions');
    }
};