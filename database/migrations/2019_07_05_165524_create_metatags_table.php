<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMetatagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('metatags', function (Blueprint $table) {
            $table->increments('id');

            // url path - without domain
            $table->string('path')->nullable();
            $table->char('locale', 5);

            // metatagable: node, term,...
            $table->integer('metatagable_id')->nullable();
            $table->string('metatagable_type')->nullable();

            // Meta-tags
            $table->string('title')->nullable();
            $table->string('keywords')->nullable();
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->text('other')->nullable();

            // SEO-fields
            $table->string('h1')->nullable();
            $table->text('seo_text')->nullable();
            $table->string('canonical')->nullable();
            $table->string('robots', 50)->nullable();

            // Fields for build XML site-map
            $table->string('changefreq', 10)->nullable();
            $table->string('priority', 10)->nullable();

            //for XML site-map "lastmod"
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
        Schema::dropIfExists('metatags');
    }
}
