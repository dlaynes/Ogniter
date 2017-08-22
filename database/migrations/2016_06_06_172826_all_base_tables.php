<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AllBaseTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if(!Schema::hasTable('players')){
            Schema::create('players', function (Blueprint $table) {
                //$table->engine = 'MyISAM';

                $table->mediumInteger('player_id')->unsigned(); //Not auto-incrementable
                $table->smallInteger('universe_id')->unsigned();

                $table->integer('alliance_id')->unsigned();
                $table->string('name', 30);
                $table->smallInteger('status')->unsigned()->default(0); //flags
                $table->integer('last_update')->unsigned();
                $table->tinyInteger('active')->unsigned(); //Pending deletion??

                $table->primary(['player_id', 'universe_id']);
                $table->index(['universe_id', 'active']); //???????
                $table->index(['universe_id', 'status', 'active']); //?????
                $table->index(['alliance_id', 'universe_id', 'active']);
            });
        }

        if(!Schema::hasTable('alliances')){
            Schema::create('alliances', function (Blueprint $table) {
                //$table->engine = 'MyISAM';

                $table->mediumInteger('alliance_id')->unsigned(); //Not auto-incrementable
                $table->smallInteger('universe_id')->unsigned();

                $table->string('name', 100);
                $table->string('tag', 20);
                $table->string('homepage', 255)->nullable();
                $table->string('logo', 255)->nullable();

                $table->tinyInteger('open')->unsigned();
                $table->integer('last_update')->unsigned();
                $table->tinyInteger('active')->unsigned(); //Pending deletion??

                $table->primary(['alliance_id', 'universe_id']);
                $table->index(['universe_id', 'active']); //???????
            });
        }

        if(!Schema::hasTable('countries')){
            Schema::create('countries', function(Blueprint $table){
                //$table->engine = 'MyISAM';

                $table->tinyInteger('id')->unsigned()->increments();
                $table->char('language',10);
                $table->string('slug',30)->nullable();
                $table->string('old_domain', 30);
                $table->string('domain', 30);
                $table->string('flag', 10);
                $table->tinyInteger('available')->unsigned()->default(1);

                //$table->primary('id');
                $table->unique('language');
            });
            DB::statement('ALTER TABLE `countries` CHANGE `id` `id` TINYINT( 3 ) UNSIGNED NOT NULL AUTO_INCREMENT primary key');
        }

        if(!Schema::hasTable('universes')){
            Schema::create('universes', function(Blueprint $table){
                //$table->engine = 'MyISAM';

                $table->smallInteger('id')->unsigned()->increments();
                $table->tinyInteger('country_id')->unsigned();

                $table->string('ogame_code',10);
                $table->string('number', 5)->nullable();
                $table->string('name',30);
                $table->char('language',2);
                $table->string('timezone',30);
                $table->string('domain',30);
                $table->string('version',16);
                $table->decimal('speed', 3, 1);
                $table->decimal('speed_fleet', 3, 1);
                $table->tinyInteger('galaxies')->unsigned();
                $table->smallInteger('systems')->unsigned();
                $table->smallInteger('extra_fields')->unsigned()->default(0);
                $table->tinyInteger('acs')->unsigned();
                $table->tinyInteger('rapidfire')->unsigned();
                $table->tinyInteger('def_to_debris')->unsigned();
                $table->decimal('debris_factor', 3, 2);
                $table->decimal('repair_factor', 3, 2);
                $table->integer('newbie_protection_limit')->unsigned();
                $table->integer('newbie_protection_high')->unsigned();
                $table->bigInteger('top_score')->unsigned();
                $table->integer('last_update')->unsigned();
                $table->tinyInteger('donut_galaxy')->unsigned()->default(0);
                $table->tinyInteger('donut_system')->unsigned()->default(0);

                $table->tinyInteger('active')->unsigned()->default(1); //0 = Exodus

                $table->smallInteger('wf_enabled')->unsigned()->default(0);
                $table->integer('wf_minimun_res_lost')->unsigned()->default(0);
                $table->smallInteger('wf_minimun_loss_perc')->unsigned()->default(0);
                $table->smallInteger('wf_basic_percentage_repair')->unsigned()->default(0);

                $table->smallInteger('api_enabled')->unsigned()->default(1);
                $table->smallInteger('api_v6_enabled')->unsigned()->default(1);

                $table->timestamps();

                //$table->primary('id');
                $table->index('country_id');
            });
            DB::statement('ALTER TABLE `universes` CHANGE `id` `id` SMALLINT( 5 ) UNSIGNED NOT NULL AUTO_INCREMENT primary key');
        }

        if(!Schema::hasTable('ranking_history')){
            //Unused for now.
            $sql = "CREATE TABLE ranking_history (
                universe_id smallint unsigned,
                entity_id mediumint unsigned,
                type tinyint unsigned,
                category tinyint unsigned,
                last_update int unsigned,
                position int unsigned default 0,
                score bigint NOT NULL default 0,
                ships int NOT NULL default 0,
                PRIMARY KEY (`universe_id`, `entity_id`, `type`, `category`, `last_update`)
            ) ENGINE=MyISAM
            PARTITION BY RANGE(last_update) (
                PARTITION minValue VALUES LESS THAN (0)
            )";
            DB::statement($sql);
            $partitioner = new \App\Ogniter\Tools\Sql\Partition\PartitionsByTimestamp();
            $partitioner->setTableName('ranking_history');
            $partitioner->addFullYearRange(2012);
            $partitioner->addFullYearRange(2013);
            $partitioner->addFullYearRange(2014);
            $partitioner->addFullYearRange(2015);
            $partitioner->addFullYearRange(2016);
            $partitioner->addYearRange(2017);
            DB::statement($partitioner->addPartitionsSQL());
            DB::statement("ALTER TABLE `ranking_history` ADD INDEX `idx` (`last_update`, `universe_id`, `type`, `category`)");
        }

        if(!Schema::hasTable('ranking_history_template')){
            $sql = "CREATE TABLE ranking_history_template (
            entity_id mediumint unsigned,
            type tinyint unsigned,
            category tinyint unsigned,
            last_update int unsigned,
            position int unsigned default 0,
            score bigint NOT NULL default 0,
            ships int NOT NULL default 0,
            PRIMARY KEY (`entity_id`, `type`, `category`, `last_update`)
            )";
            DB::statement($sql);
            DB::statement("ALTER TABLE ranking_history_template ADD INDEX hs (`type`,`category`,`last_update`)");
        }

        if(!Schema::hasTable('rankings')){
            $sql = "CREATE TABLE rankings (
            universe_id smallint unsigned,
            entity_id mediumint unsigned,
            type tinyint unsigned,
            category tinyint unsigned,
            last_update int unsigned,
            position int unsigned default 0,
            score bigint default 0,
            ships int not null default 0,
            PRIMARY KEY `pk` (`universe_id`, `entity_id`, `type`, `category`)
        )
        PARTITION BY LIST(type) (
            PARTITION t0 VALUES IN (0),
            PARTITION t1 VALUES IN (1),
            PARTITION t2 VALUES IN (2),
            PARTITION t3 VALUES IN (3),
            PARTITION t4 VALUES IN (4),
            PARTITION t5 VALUES IN (5),
            PARTITION t6 VALUES IN (6),
            PARTITION t7 VALUES IN (7)
        )";
            DB::statement($sql);
            DB::statement("ALTER TABLE `rankings` ADD `active` INT NOT NULL DEFAULT '0' AFTER `ships`");
        }

        if(!Schema::hasTable('planets')){
            Schema::create('planets', function(Blueprint $table){
                //$table->engine = 'MyISAM';

                $table->integer('planet_id')->unsigned();
                $table->smallInteger('universe_id')->unsigned();

                $table->mediumInteger('player_id')->unsigned();
                $table->string('name', 30);

                $table->tinyInteger('galaxy')->unsigned();
                $table->smallInteger('system')->unsigned();
                $table->tinyInteger('position')->unsigned();
                $table->tinyInteger('type')->unsigned();

                $table->smallInteger('size')->unsigned()->nullable();
                $table->integer('last_update')->unsigned();

                $table->tinyInteger('active')->unsigned(); //Pending deletion??

                $table->primary(['planet_id','universe_id']);
                $table->index(['universe_id','active']); //?????
                $table->index(['universe_id','galaxy','system','position','type','active']); //????
                $table->index(['universe_id','player_id','active']); //???
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
