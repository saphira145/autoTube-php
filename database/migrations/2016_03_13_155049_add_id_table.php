<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('videos', function ($table) {
            $table->bigIncrements('id');
        });
        
        Schema::table('medias', function ($table) {
            $table->bigIncrements('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('videos', function ($table) {
            $table->dropColumn('id');
        });
        
        Schema::table('medias', function ($table) {
            $table->dropColumn('id');
        });
    }
}
