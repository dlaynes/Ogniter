<?php

namespace App\Ogniter\Api\Remote\Ogame\Command;

use App\Ogniter\Api\Remote\Ogame\Helper\DataBuilder;
use App\Ogniter\Api\Remote\Ogame\Task\Process\HighscoreUpdateTask;
use App\Ogniter\Api\Remote\Ogame\Task\Request\HighscoreRequest;
use App\Ogniter\Model\Ogame\Highscore;
use App\Ogniter\Model\Ogame\Universe;
use App\Ogniter\Model\Ogame\UniverseMeta;
use App\Ogniter\Model\Ogame\Update;
use App\Ogniter\Tools\File\CsvBuilder;
use App\Ogniter\Tools\Sql\Query\LoadData;
use App\Ogniter\Tools\Timer\TimerBag;

use Illuminate\Console\Command;
use Mockery\CountValidator\Exception;

class HighscoreQueue extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ogame:highscore-queue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Loads pending info from the highscore of Api-enabled Ogame Universes';

    public function handle(Update $updateModel, TimerBag $timer)
    {
        ini_set('memory_limit', '1024M');

        /* Install Highscore */
        $time = time();
        $meta = UniverseMeta::getUniverseForHighscoreUpdate($time - Update::EXPIRATION_RANKING_HISTORY);
        if(!$meta){
            $this->comment("Highscore Queue ended. No pending updates for now".PHP_EOL);
            return;
        }

        $timer->addTask('highscore-'.$meta->universe_id);
        $update = $updateModel->newIfNotAvailable($meta->universe_id, Update::UPDATE_RANKING_HISTORY);

        //$this->comment("Highscore - Universe #".$meta->universe_id);
        try {
            for ($c = 1; $c < 3; $c++) {
                for ($t = 0; $t < 8; $t++) {
                    //$this->comment("Reading data from ".$c.'-'.$t);

                    $u = $updateModel->newIfNotAvailable($meta->universe_id, Update::UPDATE_RANKING, $c, $t);
                    if($u->last_update > ( time() - Update::EXPIRATION_RANKING_HISTORY ) ){
                        //Don't fill again the data from today
                        continue;
                    }

                    $highscoreTask = new HighscoreUpdateTask(
                        new DataBuilder(new CsvBuilder(), new LoadData()),
                        new DataBuilder(new CsvBuilder(), new LoadData()),
                        new DataBuilder(new CsvBuilder(), new LoadData()),
                        new HighscoreRequest());
                    $highscoreTask
                        ->setUpdateModel($u)
                        ->setUniverseId($meta->universe_id)
                        ->setCategory($c)
                        ->setLanguage($meta->language)
                        ->setTimestamp($time)
                        ->setType($t)
                        ->setDomain($meta->domain);
                    $highscoreTask->run();

                    /*
                     * Not needed, I think. The XML loaders are throwing an exception if the document is malformed
                    if($c==1 && $t==0){
                        if(Highscore::countFromUpdate($meta->language, $meta->universe_id, $c, $t, $time) < 1){
                            throw new Exception("Remote server ".$meta->universe_id.
                                " (".$meta->domain.") is down, retrying later".PHP_EOL);
                        }
                    }
                    */

                    if ($c != 2 && $t != 7) sleep(2);
                }
            }

            //$tbl_name = Universe::getHighscoreTableName($meta->language, $meta->universe_id);
            
            //Since the remote data loading is failing constantly, we cannot trust this syncronization
            //Highscore::replaceRankings($tbl_name, $meta->universe_id, $time);

            //We are moving these queries to the task instead
            //Highscore::replaceRankingHistory($tbl_name, $meta->universe_id, $time);
            //Eventually, we won't need the z_*_ranking_history tables

            $meta->previous_global_update = $meta->last_global_update; //this is no longer required
            $meta->last_global_update = $time; //neither this, since we are storing the scan record in the ranking_updates table
            $meta->save();

            $update->last_update = $time;
            $update->save();

        } catch(Exception $e ){
            $this->error("Highscore Update Error: ".$e->getMessage());
            $update->last_update = $time - $updateModel->getExpirationDelay($update->update_type_id);
            $update->save();

        } finally {
            $timer->stopTask('highscore-'.$meta->universe_id);
            $item = $timer->getItem('highscore-'.$meta->universe_id);

            $this->comment("Highscore update in universe #".$meta->universe_id.' ('.$meta->domain.") took ".$item->getDifference()."s");
        }
    }

}
