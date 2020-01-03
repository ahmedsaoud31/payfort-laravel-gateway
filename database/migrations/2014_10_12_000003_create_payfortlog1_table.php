<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayfortlog1Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payfortlog1', function (Blueprint $table) {
			$table->increments('id');
			$table->string('environment');
			$table->string('merchant_reference');
			$table->string('response_code')->nullable();
			$table->string('response_message')->nullable();
			$table->string('token_name')->nullable();
			$table->string('ip')->nullable();
			$table->decimal('amount', 10, 2)->default(0);
			$table->string('response_code2')->nullable();
			$table->string('response_message2')->nullable();
			$table->string('fort_id')->nullable();
			$table->string('authorization_code')->nullable();
			$table->timestamps();
			$table->integer('payment_id')->unsigned();
			$table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payfortlog1');
    }
}
