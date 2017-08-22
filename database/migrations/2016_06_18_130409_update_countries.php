<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCountries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('countries','api_domain')){
            Schema::table('countries', function (Blueprint $table) {
                $table->string('language', 10)->change();
                $table->string('flag', 10)->change();
                $table->string('api_domain', 30);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
