<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OldTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `polls` (
         `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
         `question` varchar(200) NOT NULL,
         `checkbox` tinyint(3) unsigned NOT NULL DEFAULT '0',
         `format` tinyint(3) unsigned NOT NULL DEFAULT '1',
         `active` tinyint(3) unsigned NOT NULL,
         `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
         PRIMARY KEY (`id`),
         KEY `active` (`active`)
        ) DEFAULT CHARSET=utf8";
        DB::statement($sql);

        $sql = "CREATE TABLE IF NOT EXISTS `poll_answers` (
         `poll_id` int(10) unsigned NOT NULL,
         `value` varchar(20) NOT NULL,
         `answer` varchar(100) NOT NULL,
         `votes` int(10) unsigned NOT NULL,
         PRIMARY KEY (`poll_id`,`value`)
        ) DEFAULT CHARSET=utf8";
        DB::statement($sql);

        $sql = "CREATE TABLE IF NOT EXISTS `sites` (
         `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
         `name` varchar(100) NOT NULL,
         `description` text NOT NULL,
         `review` text,
         `image` varchar(50) NOT NULL,
         `url` varchar(100) NOT NULL,
         `votes` int(10) unsigned NOT NULL,
         `score` int(10) unsigned NOT NULL,
         PRIMARY KEY (`id`),
         KEY `votes` (`votes`),
         KEY `score` (`score`)
        ) DEFAULT CHARSET=utf8";
        DB::statement($sql);

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
