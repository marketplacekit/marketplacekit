<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_providers', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name')->nullable();
            $table->string('key')->nullable();
            $table->string('icon')->nullable();
            $table->string('display_name')->nullable();
            $table->text('description')->nullable();
            $table->text('payment_instructions')->nullable();


            $table->string('client_id')->nullable();
            $table->string('client_secret')->nullable();
            $table->string('connection_url')->nullable();

            $table->integer('position')->nullable()->default(0);
            $table->boolean('is_offline')->nullable()->default(0);
            $table->boolean('is_enabled')->nullable();

            $table->json('extra_attributes')->nullable();
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
        Schema::dropIfExists('payment_providers');
    }
}
