<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdvancedToPageTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('page_translations', function (Blueprint $table) {
            //
            $table->boolean('is_advanced')->nullable()->after('visible');
            $table->string('route')->nullable()->after('slug');
            $table->text('raw_content')->nullable()->after('content');
            $table->json('extra_attributes')->nullable()->after('visible');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('page_translations', function (Blueprint $table) {
            //
            $table->dropColumn('is_advanced');
            $table->dropColumn('route');
            $table->dropColumn('raw_content');
            $table->dropColumn('extra_attributes');
        });
    }
}
