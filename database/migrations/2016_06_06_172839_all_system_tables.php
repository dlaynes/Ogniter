<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AllSystemTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        $sql = "CREATE TABLE IF NOT EXISTS `ranking_updates` (
                  `universe_id` smallint(5) NOT NULL,
                  `updated_on` int(10) unsigned NOT NULL,
                  `category` tinyint(1) NOT NULL,
                  `type` tinyint(1) NOT NULL,
                  UNIQUE KEY `keys` (`universe_id`,`updated_on`,`category`,`type`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
        DB::statement($sql);


        if(!Schema::hasTable('settings')){
            Schema::create('settings', function(Blueprint $table){
                //$table->engine = 'MyISAM';
                $table->string('key');
                $table->string('value');

                $table->primary('key');
            });
        }

        if(!Schema::hasTable('searches')){
            Schema::create('searches', function(Blueprint $table){
                //$table->engine = 'MyISAM';

                $table->integer('id')->unsigned()->increments();
                $table->smallInteger('universe_id')->unsigned();
                $table->string('text',100);
                $table->string('slug',100);
                $table->integer('repeated')->unsigned()->default(0);
                $table->integer('last_update')->unsigned()->nullable();

                //$table->primary('id');
                $table->index('universe_id');
            });
            //DB::query('ALTER TABLE `searches` CHANGE `id` `id` INT( 10 ) UNSIGNED NOT NULL AUTO_INCREMENT');
        }

        if(!Schema::hasTable('meta_universes')){
            Schema::create('meta_universes', function(Blueprint $table){
                //$table->engine = 'MyISAM';

                $table->smallInteger('universe_id')->unsigned();

                $table->string('local_name',30);
                $table->tinyInteger('show_in_global_stats')->unsigned()->default(1);
                $table->tinyInteger('weight')->unsigned();

                $table->mediumInteger('num_players')->unsigned();
                $table->mediumInteger('num_alliances')->unsigned();
                $table->mediumInteger('num_planets')->unsigned();
                $table->mediumInteger('num_moons')->unsigned();
                $table->mediumInteger('normal_players')->unsigned();
                $table->mediumInteger('inactive_players')->unsigned();
                $table->mediumInteger('inactive_30_players')->unsigned();
                $table->mediumInteger('outlaw_players')->unsigned();
                $table->mediumInteger('vacation_players')->unsigned();
                $table->mediumInteger('suspended_players')->unsigned();
                $table->tinyInteger('is_special')->unsigned();

                // Previous and current updates
                $table->integer('last_global_update')->unsigned();
                $table->integer('previous_global_update')->unsigned();

                $table->primary('universe_id');
            });
        }

        if(!Schema::hasTable('statistics_history')){
            Schema::create('statistics_history', function(Blueprint $table){
                //$table->engine = 'MyISAM';

                $table->tinyInteger('country_id')->unsigned();
                $table->smallInteger('universe_id')->unsigned();

                $table->mediumInteger('num_players')->unsigned();
                $table->mediumInteger('num_alliances')->unsigned();
                $table->mediumInteger('num_planets')->unsigned();
                $table->mediumInteger('num_moons')->unsigned();
                $table->mediumInteger('normal_players')->unsigned();
                $table->mediumInteger('inactive_players')->unsigned();
                $table->mediumInteger('inactive_30_players')->unsigned();
                $table->mediumInteger('outlaw_players')->unsigned();
                $table->mediumInteger('vacation_players')->unsigned();
                $table->mediumInteger('suspended_players')->unsigned();

                $table->date('added_on');

                $table->primary(['country_id','universe_id', 'added_on']);
            });
        }

        if(!Schema::hasTable('server_queue')){
            Schema::create('server_queue', function(Blueprint $table){
                //$table->engine = 'MyISAM';

                $table->smallInteger('id')->increments();
                $table->string('domain',30);
                $table->integer('processed')->unsigned();

                //$table->primary('id');
                $table->index('processed');
            });
            DB::statement('ALTER TABLE `server_queue` CHANGE `id` `id` SMALLINT( 6 ) NOT NULL AUTO_INCREMENT primary key');
        }


        if(!Schema::hasTable('updates')){
            Schema::create('updates', function(Blueprint $table){
                //$table->engine = 'MyISAM';
                $table->smallInteger('universe_id')->unsigned();
                $table->tinyInteger('update_type_id')->unsigned();
                $table->tinyInteger('category')->unsigned()->nullable();
                $table->tinyInteger('type')->unsigned()->nullable();
                $table->integer('last_update')->unsigned();
                $table->tinyInteger('updating')->unsigned()->nullable();
                $table->integer('updating_on')->unsigned()->default(0);
                $table->timestamps();

                $table->unique(['universe_id','update_type_id','category','type']);
            });
            DB::unprepared("ALTER TABLE `updates` ADD `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST ");
        }

        //Mostly unused
        if(!Schema::hasTable('update_types')){
            Schema::create('update_types', function(Blueprint $table){
                //$table->engine = 'MyISAM';
                $table->tinyInteger('id')->unsigned()->increments();
                $table->string('description', 10);

                $table->primary('id');
            });
            DB::table('update_types')->insert([
                'id' => 1,
                'description' => 'Universe'
            ]);
            DB::table('update_types')->insert([
                'id' => 2,
                'description' => 'Planet'
            ]);
            DB::table('update_types')->insert([
                'id' => 3,
                'description' => 'Alliance'
            ]);
            DB::table('update_types')->insert([
                'id' => 4,
                'description' => 'Player'
            ]);
            DB::table('update_types')->insert([
                'id' => 5,
                'description' => 'Ranking'
            ]);
            DB::table('update_types')->insert([
                'id' => 6,
                'description' => 'Country'
            ]);
        }

        if(!Schema::hasTable('jobs')){
            Schema::create('jobs', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('queue');
                $table->longText('payload');
                $table->tinyInteger('attempts')->unsigned();
                $table->tinyInteger('reserved')->unsigned();
                $table->unsignedInteger('reserved_at')->nullable();
                $table->unsignedInteger('available_at');
                $table->unsignedInteger('created_at');
                $table->index(['queue', 'reserved', 'reserved_at']);
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
