<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('payments', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('processor');
            $table->integer('user_id')->nullable();
            $table->integer('amount')->nullable();
            $table->string('currency')->nullable();
            $table->morphs('payable');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::drop('payments');
    }
}
