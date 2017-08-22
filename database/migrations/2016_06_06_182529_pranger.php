<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Pranger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('banned_users')){
            Schema::create('banned_users', function(Blueprint $table){
                //$table->engine = 'MyISAM';

                $table->integer('id')->increments();
                $table->integer('player_id')->unsigned();
                $table->smallInteger('universe_id')->unsigned();

                $table->integer('added_on')->unsigned();
                $table->integer('removed_on')->unsigned();

                $table->index(['universe_id','player_id']);
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
