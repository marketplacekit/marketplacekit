<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWalletTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('wallet_id');

            $table->integer('amount'); // amount is an integer, it could be "dollars" or "cents"
            $table->string('hash', 60); // hash is a uniqid for each transaction
            $table->string('type', 30); // type can be anything in your app, by default we use "deposit" and "withdraw"
            $table->boolean('accepted'); // All transactions will be added in the book, some can be refused
            $table->json('meta')->nullable(); // Add all kind of meta information you need

            $table->timestamps();

            $table->foreign('wallet_id')->references('id')->on('wallets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wallet_transactions');
    }
}
