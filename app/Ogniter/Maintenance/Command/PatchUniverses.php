<?php

namespace App\Ogniter\Maintenance\Command;

use App\Ogniter\Model\Ogame\BannedUsers;
use App\Ogniter\Model\Ogame\Highscore;
use App\Ogniter\Model\Ogame\HighscoreLog;
use App\Ogniter\Model\Ogame\Player;
use App\Ogniter\Model\Ogame\Universe;
use App\Ogniter\Model\Ogame\Update;
use App\Ogniter\Tools\Timer\TimerBag;
use Illuminate\Console\Command;
use DB;

class PatchUniverses extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ogame:patch-universes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Patches universes';

    /**
     * The console command signature
     *
     * @var string
     */
    protected $signature = 'ogame:patch-universes {active=1} {greater_than?} {lower_than?}';

    public function handle(Update $updateModel, Universe $universeModel, TimerBag $timer)
    {
        $active = (int) $this->argument('active');
        $greater_than = (int) $this->argument('greater_than');
        $lower_than = $this->argument('lower_than');

        $qu = $universeModel->select('id','ogame_code','local_name','weight','is_special','universes.domain')
            ->join('meta_universes AS m','m.universe_id','=','universes.id');

        if($greater_than){
            $qu->where('universes.id','>',$greater_than);
        }
        if($lower_than){
            $qu->where('universes.id','<',$lower_than);
        }

        $qu->where('active','=',$active)->where('active','=',$active);
        $universes = $qu->get();

        $this->info("Looking for universes with status: ".$active);
        if (!$this->confirm('Do you wish to continue? [y|N]')) {
            return;
        }

        $bar = $this->output->createProgressBar(count($universes));
        
        //DB::unprepared("ALTER TABLE ranking_history_template ADD INDEX hs (`type`,`category`,`last_update`)");
        //DB::unprepared("ALTER TABLE rankings CHANGE `ships` `ships` INT NOT NULL DEFAULT '0'");

        $timer->addTask('patch-universes');
        foreach($universes as $universe) {
            $language = substr($universe->ogame_code, 0, 2);
            $server_num = substr($universe->ogame_code, 2);
            $universe_id = (int) $universe->id;
            $table_name = Universe::getHighscoreTableName($language, $universe_id);

            $this->line("\nChecking universe #".$universe_id);

            $timer->addTask('add-history-table-'.$universe_id);
            $highscore_sql = Universe::createHighscoreTableSQL($language, $universe_id);
            \DB::statement($highscore_sql);
            
            $timer->stopTask('add-history-table-'.$universe_id);
            $duration = $timer->getItem('add-history-table-'.$universe_id);
            $this->comment("Creating the history table in universe #".$universe_id." (".$universe->domain.") took ". $duration->getDifference()."s");

            //Rellenar tablas de updates
            //$timer->addTask('add-update-records-'.$universe_id);
            //$u = new Update();
            //$u->initUniverseUpdateRecords($universe_id);
            //$timer->stopTask('add-update-records-'.$universe_id);
            //$duration = $timer->getItem('add-update-records-'.$universe_id);
            //$this->comment("Fillin'-in Update records from universe #".$universe_id." (".$universe->domain.") took ". $duration->getDifference()."s");
            

            /*
            //Tablas clon InnoDB
            $timer->addTask('add-innodb-'.$universe_id);
            $sql = "CREATE TABLE IF NOT EXISTS `{$table_name}_innodb` (
              `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
              `entity_id` mediumint(8) unsigned NOT NULL,
              `type` tinyint(3) unsigned NOT NULL,
              `category` tinyint(3) unsigned NOT NULL,
              `last_update` int(10) unsigned NOT NULL,
              `position` mediumint(8) unsigned DEFAULT '0',
              `score` bigint(20) NOT NULL DEFAULT '0',
              `ships` int(11) NOT NULL DEFAULT '0',
              KEY `idx` (`entity_id`,`type`,`category`,`last_update`),
              KEY `hs` (`type`,`category`,`last_update`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
            DB::unprepared($sql);
            $timer->stopTask('add-innodb-'.$universe_id);
            $duration = $timer->getItem('add-innodb-'.$universe_id);
            $this->comment("Creating highscore table from universe #".$universe_id." (".$universe->domain.") took ". $duration->getDifference()."s");
            
            $timer->addTask('fill-innodb-'.$universe_id);
            DB::unprepared("INSERT INTO `{$table_name}_innodb` (entity_id, type, category, last_update, position, score, ships)
              SELECT entity_id, type, category, last_update, position, score, ships
              FROM {$table_name}");
            $timer->stopTask('fill-innodb-'.$universe_id);
            $duration = $timer->getItem('fill-innodb-'.$universe_id);
            $this->comment("Filling highscore table from universe #".$universe_id." (".$universe->domain.") took ". $duration->getDifference()."s");
            */

            /*
            //Rellenar la gran tabla ranking_history
            $timer->addTask('fill-in-rh-from-'.$universe_id);
            $sql = "INSERT IGNORE INTO ranking_history (`universe_id`,`entity_id`,`type`,`category`,`last_update`,
              `position`,`score`,`ships`)
              SELECT {$universe_id}, z.`entity_id`,z.`type`,z.`category`,z.`last_update`,
              z.`position`,z.`score`,z.`ships` FROM {$table_name} AS z";
            DB::unprepared($sql);
            $timer->stopTask('fill-in-rh-from-'.$universe_id);
            $duration = $timer->getItem('fill-in-rh-from-'.$universe_id);
            $this->comment("Fillin-in ranking_history from universe #".$universe_id." (".$universe->domain.") took ". $duration->getDifference()."s");
            */

            /*
            //Lapsus (?), corregir last_update
            $timer->addTask('pay-attention-next-time-'.$universe_id);
            Highscore::resetRankings($universe_id);
            for($cat=1;$cat<3;$cat++){
                for($type=0;$type<8;$type++){
                    $last_update = HighscoreLog::getLatestRankingUpdate($universe_id, $cat, $type);
                    if(!$last_update){
                        $last_update = "(SELECT MAX(last_update) FROM {$table_name} USE INDEX(hs) WHERE type={$type} AND category=$cat)";
                    }
                    DB::statement("REPLACE INTO rankings
                    (universe_id, entity_id, type, category, last_update, position, score, ships, active)
                      SELECT {$universe_id}, entity_id, type, category, last_update, position,	score, ships, 1
                      FROM {$table_name} WHERE last_update={$last_update}");
                }
            }
            $timer->stopTask('pay-attention-next-time-'.$universe_id);
            $duration = $timer->getItem('pay-attention-next-time-'.$universe_id);
            $this->comment("Patching table rankings from universe #".$universe_id." (".$universe->domain.") took ". $duration->getDifference()."s");
            */

            /*
            //No es necesaria la clave primaria (?)
            $table_name = Universe::getHighscoreTableName($language, $universe_id);
            DB::unprepared('ALTER TABLE '.$table_name.' DROP PRIMARY KEY, ADD INDEX `idx` (`entity_id`, `type`, `category`, `last_update`) USING BTREE');
            */

            /*
            //Evitar problemas con las consultas del top-flop
            $timer->addTask('patch-hs-ships-'.$universe_id);
            $table_name = Universe::getHighscoreTableName($language, $universe_id);
            DB::unprepared("ALTER TABLE ".$table_name." CHANGE `ships` `ships` INT NOT NULL DEFAULT '0'");
            $timer->stopTask('patch-hs-ships-'.$universe_id);
            $duration = $timer->getItem('patch-hs-ships-'.$universe_id);
            $this->comment("Fixing table in universe #".$universe_id." (".$universe->domain.") took ". $duration->getDifference()."s");
            */

            /*
            //rellenar tabla ranking_updates con datos existentes
            $timer->addTask('patch-r-updates-'.$universe_id);
            $table_name = Universe::getHighscoreTableName($language, $universe_id);
            DB::unprepared('REPLACE INTO ranking_updates (universe_id, updated_on, category, type)
                SELECT '.$universe_id.', last_update, category, type FROM '.$table_name.' GROUP BY last_update, category, type');
            $timer->stopTask('patch-r-updates-'.$universe_id);
            $duration = $timer->getItem('patch-r-updates-'.$universe_id);
            $this->comment("Fixing table in universe #".$universe_id." (".$universe->domain.") took ". $duration->getDifference()."s");
            */

            /*
            //Agregar indice highscore
            $timer->addTask('patch-hs-index-'.$universe_id);
            $table_name = Universe::getHighscoreTableName($language, $universe->id);
            DB::unprepared("ALTER TABLE ".$table_name." ADD INDEX hs (`type`,`category`,`last_update`)");
            $timer->stopTask('patch-hs-index-'.$universe_id);
            $duration = $timer->getItem('patch-hs-index-'.$universe_id);
            $this->comment("Fixing table in universe #".$universe_id." (".$universe->domain.") took ". $duration->getDifference()."s");
            */

            //Corregir puntajes negativos
            //$timer->addTask('patch-score-'.$universe_id);
            //$table_name = Universe::getHighscoreTableName($language, $universe->id);
            //DB::unprepared("ALTER TABLE ".$table_name." CHANGE `score` `score` BIGINT(20) NOT NULL DEFAULT '0'");
            //$timer->stopTask('patch-score-'.$universe_id);
            //$duration = $timer->getItem('patch-score-'.$universe_id);
            //$this->comment("Fixing table in universe #".$universe_id." (".$universe->domain.") took ". $duration->getDifference()."s");

            /*
            //Rellenar Pranger
            $timer->addTask('patch-pranger-'.$universe_id);
            $update_times = DB::select("SELECT DISTINCT modified_on FROM player_changes
              WHERE universe_id={$universe_id}
              ORDER BY modified_on ASC");
            foreach($update_times as $tm){
                //"Current player status"
                $players = DB::select("SELECT * FROM player_changes WHERE modified_on=".$tm->modified_on);

                foreach($players as $player){
                    $changes = DB::selectOne("SELECT pc.name, pc.alliance_id, pc.status FROM `player_changes` AS pc
                        WHERE pc.universe_id={$universe_id} AND pc.player_id={$player->player_id}
                        AND modified_on < {$tm->modified_on}
                        ORDER BY modified_on DESC limit 1");

                    if($changes){
                        if(!($changes->status & Player::STATUS_BANNED) && ($player->status & Player::STATUS_BANNED) ){
                            BannedUsers::insert([
                                'universe_id' => $universe_id,
                                'player_id' => $player->player_id,
                                'added_on' => $tm->modified_on,
                                'removed_on' => 0
                            ]);
                        } elseif(($changes->status & Player::STATUS_BANNED) && !($player->status & Player::STATUS_BANNED)){
                            $last = BannedUsers::getLastBanFromUser($universe_id, $player->player_id);
                            if($last){
                                $last->removed_on = $tm->modified_on;
                                $last->save();
                            }
                        }
                    }
                }
            }
            $timer->stopTask('patch-pranger-'.$universe_id);
            $duration = $timer->getItem('patch-pranger-'.$universe_id);
            $this->comment("Pranger update in universe #".$universe_id." (".$universe->domain.") took ". $duration->getDifference()."s");
            */

            /*
            $updateModel->initUniverseUpdateRecords($universe_id);
            */
            $bar->advance();
            sleep(2);
        }
        $bar->finish();

        $timer->stopTask('patch-universes');
        $item = $timer->getItem('patch-universes');
        $this->info(PHP_EOL."Universe patching just ended. It took ".$item->getDifference()."s".PHP_EOL);

    }

}