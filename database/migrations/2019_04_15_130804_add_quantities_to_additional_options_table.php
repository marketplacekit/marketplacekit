<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddQuantitiesToAdditionalOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('listing_additional_options', function (Blueprint $table) {
            //
            $table->integer('max_quantity')->nullable()->after('price');
            $table->integer('min_quantity')->nullable()->after('price');
            $table->json('meta')->after('position')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('listing_additional_options', function (Blueprint $table) {
            //
            $table->dropColumn('min_quantity');
            $table->dropColumn('max_quantity');
            $table->dropColumn('meta');
        });
    }
}
