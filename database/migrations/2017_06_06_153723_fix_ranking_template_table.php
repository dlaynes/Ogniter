<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixRankingTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("ALTER TABLE `ranking_history` CHANGE `score` `score` BIGINT(20) NULL DEFAULT '0'");
        DB::unprepared("ALTER TABLE `ranking_history_template` CHANGE `score` `score` BIGINT(20) NULL DEFAULT '0'");
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
