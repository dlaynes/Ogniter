<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AllChangeTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `player_changes` (
         `player_id` mediumint(8) unsigned NOT NULL,
         `universe_id` smallint(5) unsigned NOT NULL,
         `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
         `alliance_id` int(10) unsigned NOT NULL,
         `status` smallint(5) unsigned NOT NULL DEFAULT '0',
         `modified_on` int(10) unsigned NOT NULL,
         KEY (`player_id`,`universe_id`)
        ) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
        DB::statement($sql);

        $sql = "CREATE TABLE IF NOT EXISTS `alliance_changes` (
         `alliance_id` mediumint(8) unsigned NOT NULL,
         `universe_id` smallint(5) unsigned NOT NULL,
         `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
         `tag` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
         `open` tinyint(3) unsigned NOT NULL,
         `modified_on` int(10) unsigned NOT NULL,
         KEY (`alliance_id`,`universe_id`)
        ) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
        DB::statement($sql);

        $sql = "CREATE TABLE IF NOT EXISTS `planet_changes` (
         `planet_id` int(10) unsigned NOT NULL,
         `universe_id` smallint(5) unsigned NOT NULL,
         `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
         `gal` tinyint(3) unsigned NOT NULL,
         `sys` smallint(5) unsigned NOT NULL,
         `pos` tinyint(3) unsigned NOT NULL,
         `modified_on` int(10) unsigned NOT NULL,
         KEY (`planet_id`,`universe_id`)
        ) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
        DB::statement($sql);

        if(!Schema::hasTable('players_meta')){
            Schema::create('players_meta', function(Blueprint $table){
                //$table->engine = 'MyISAM';

                $table->mediumInteger('player_id')->unsigned();
                $table->smallInteger('universe_id')->unsigned();

                $table->integer('views')->unsigned()->default(0);

                $table->primary(['player_id','universe_id']);
            });     
        }

        if(!Schema::hasTable('alliance_meta')){
            Schema::create('alliance_meta', function(Blueprint $table){
                //$table->engine = 'MyISAM';

                $table->mediumInteger('alliance_id')->unsigned();
                $table->smallInteger('universe_id')->unsigned();
                $table->integer('views')->unsigned()->default(0);

                $table->primary(['alliance_id','universe_id']);
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
