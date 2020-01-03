<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name')->nullable();
			$table->string('ccname')->nullable();
			$table->string('email')->nullable();
			$table->decimal('amount', 10, 2);
			$table->decimal('fee', 10, 2);
			$table->string('currency');
			$table->text('comment')->nullable();
			$table->string('status')->nullable();
			$table->string('fort_id')->nullable();
			$table->string('payment_id')->nullable();
			$table->string('merchant_reference')->nullable();
			$table->timestamps();
			$table->timestamp('paid_at')->nullable();
			$table->Integer('user_id')->unsigned()->nullable();
			$table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
