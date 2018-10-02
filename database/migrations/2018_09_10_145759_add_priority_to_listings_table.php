<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPriorityToListingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('listings', function (Blueprint $table) {
            //
            $table->dateTime('bold_until')->after('spotlight')->nullable();
            $table->dateTime('priority_until')->after('spotlight')->nullable();
            $table->dateTime('expires_at')->after('spotlight')->nullable();
            $table->boolean('is_draft')->after('is_published')->default(0);
            $table->integer('photos_limit')->after('timeslots')->nullable();
            $table->decimal('price_ex_vat', 11, 2)->after('price')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropColumn(['priority_until', 'expires_at']);
        });
    }
}
