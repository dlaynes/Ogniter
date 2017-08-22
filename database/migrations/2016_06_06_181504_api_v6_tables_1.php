<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ApiV6Tables1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $building_list = Config::get('ogame.building_list');
        $research_list = Config::get('ogame.research_list');
        $ship_list = Config::get('ogame.ship_list');
        $defense_list = Config::get('ogame.defense_list');

        if(!Schema::hasTable('api6_reports')){
            $sql = "CREATE TABLE api6_reports (
                id INT unsigned NOT NULL AUTO_INCREMENT,
                user_id int unsigned NOT NULL,
                universe_id int unsigned NOT NULL,
                ogame_id varchar(40) NOT NULL,
                report_type tinyint unsigned NOT NULL,
                created_on int unsigned,
                status tinyint unsigned,
                PRIMARY KEY (`id`),
                UNIQUE KEY (`ogame_id`)
            ) ENGINE=MyISAM";
            DB::statement($sql);
        }

        //TODO: api6_battle_info

        if(!Schema::hasTable('api6_espionage_info')){
            $sql = "CREATE TABLE IF NOT EXISTS api6_espionage_info (
                report_id int unsigned not null,
                total_ship_count bigint unsigned not null,
                total_defense_count bigint unsigned not null,
                loot_percentage tinyint unsigned not null,
                spy_fail_change tinyint unsigned not null,
                activity tinyint unsigned not null,
                attacker_name varchar(30),
                attacker_planet_name varchar(30),
                attacker_galaxy tinyint unsigned not null,
                attacker_system smallint unsigned not null,
                attacker_planet tinyint unsigned not null,
                attacker_planet_type tinyint unsigned not null,
                attacker_alliance_name varchar(100),
                attacker_alliance_tag varchar(20),
                defender_name varchar(30),
                defender_planet_name varchar(30),
                defender_galaxy tinyint unsigned not null,
                defender_system smallint unsigned not null,
                defender_planet tinyint unsigned not null,
                defender_planet_type tinyint unsigned not null,
                defender_alliance_name varchar(100),
                defender_alliance_tag varchar(20),
                failed_ships tinyint unsigned not null,
                failed_defense tinyint unsigned not null,
                failed_buildings tinyint unsigned not null,
                failed_research tinyint unsigned not null,
                metal int unsigned not null,
                crystal int unsigned not null,
                deuterium int unsigned not null,
                PRIMARY KEY (`report_id`)
            ) ENGINE=MyISAM";
            DB::statement($sql);
        }

        //Most of the time several parts of the report will be empty, so...
        if(!Schema::hasTable('api6_espionage_info_buildings')){
            $fields = '';
            foreach($building_list as $building){
                $fields .= '            b'.$building.' tinyint unsigned not null default 0,'."\n";
            }
            $sql = "CREATE TABLE api6_espionage_info_buildings (
                report_id int unsigned not null,
                $fields
                PRIMARY KEY (`report_id`)
            ) ENGINE=MyISAM";
            DB::statement($sql);
        }

        if(!Schema::hasTable('api6_espionage_info_research')){
            $fields = '';
            foreach($research_list as $research){
                $fields .= '            r'.$research.' tinyint unsigned not null default 0,'."\n";
            }
            $sql = "CREATE TABLE api6_espionage_info_research (
                report_id int unsigned not null,
                $fields
                PRIMARY KEY (`report_id`)
            ) ENGINE=MyISAM";
            DB::statement($sql);
        }

        if(!Schema::hasTable('api6_espionage_info_ships')){
            $fields = '';
            foreach($ship_list as $ship){
                $fields .= '            s'.$ship.' int unsigned not null default 0,'."\n";
            }
            $sql = "CREATE TABLE api6_espionage_info_ships (
                report_id int unsigned not null,
                $fields
                PRIMARY KEY (`report_id`)
            ) ENGINE=MyISAM";
            DB::statement($sql);
        }


        if(!Schema::hasTable('api6_espionage_info_defense')){
            $fields = '';
            foreach($defense_list as $defense){
                $fields .= '            d'.$defense.' int unsigned not null default 0,'."\n";
            }
            $sql = "CREATE TABLE api6_espionage_info_defense (
                report_id int unsigned not null,
                $fields
                PRIMARY KEY (`report_id`)
            ) ENGINE=MyISAM";
            DB::statement($sql);
        }


        //Harvest data
        $sql = "CREATE TABLE IF NOT EXISTS api6_harvest_info (
            report_id int unsigned not null,
            galaxy tinyint unsigned not null,
            system smallint unsigned not null,
            planet tinyint unsigned not null,
            recycler_count int unsigned not null,
            recycler_capacity int unsigned not null,
            metal_in_debris int unsigned not null,
            crystal_in_debris int unsigned not null,
            metal_retrieved int unsigned not null,
            crystal_retrieved int unsigned not null,
            owner_name varchar(30),
            owner_alliance_name varchar(100),
            owner_alliance_tag varchar(20),
            PRIMARY KEY (`report_id`)
        ) ENGINE=MyISAM";
        DB::statement($sql);

        if(!Schema::hasTable('api6_missile_info')){
            $defense_data = '';
            foreach($defense_list as $defense){
                $defense_data .= '            od'.$defense.' int unsigned not null default 0,'."\n";
            }

            $destroyed_defense_data = '';
            foreach($defense_list as $defense){
                $destroyed_defense_data .= '            dd'.$defense.' int unsigned not null default 0,'."\n";
            }
            $sql = "CREATE TABLE api6_missile_info (
                report_id int unsigned not null,
                attacker_name varchar(30),
                attacker_planet_name varchar(30),
                attacker_galaxy tinyint unsigned not null,
                attacker_system smallint unsigned not null,
                attacker_planet tinyint unsigned not null,
                attacker_planet_type tinyint unsigned not null,
                defender_name varchar(30),
                defender_planet_name varchar(30),
                defender_galaxy tinyint unsigned not null,
                defender_system smallint unsigned not null,
                defender_planet tinyint unsigned not null,
                defender_planet_type tinyint unsigned not null,
                missiles_lost_attacker int unsigned not null,
                missiles_lost_defender int unsigned not null,
                $defense_data
                $destroyed_defense_data
                PRIMARY KEY (`report_id`)
            ) ENGINE=MyISAM";
            DB::statement($sql);
        }

        //Track hackers
        if(!Schema::hasTable('api6_failed_reports')){
            Schema::create('api6_failed_reports', function(Blueprint $table){
                //$table->engine = 'MyISAM';
                $table->integer('id')->unsigned()->increments();
                $table->string('ogame_id');
                $table->smallInteger('universe_id')->unsigned();
                $table->integer('user_id')->unsigned();
                $table->smallInteger('status')->unsigned();
                $table->tinyInteger('attempts')->unsigned();
                $table->tinyInteger('active')->unsigned();
                $table->integer('ip')->unsigned();
                $table->integer('modified_on')->unsigned();
                $table->unique('ogame_id');
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
