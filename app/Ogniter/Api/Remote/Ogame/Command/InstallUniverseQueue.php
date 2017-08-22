<?php

namespace App\Ogniter\Api\Remote\Ogame\Command;


use App\Ogniter\Api\Remote\Ogame\Helper\DataBuilder;
use App\Ogniter\Api\Remote\Ogame\Task\Process\AllianceUpdateTask;
use App\Ogniter\Api\Remote\Ogame\Task\Process\HighscoreUpdateTask;
use App\Ogniter\Api\Remote\Ogame\Task\Process\PlanetUpdateTask;
use App\Ogniter\Api\Remote\Ogame\Task\Process\PlayerUpdateTask;
use App\Ogniter\Api\Remote\Ogame\Task\Request\HighscoreRequest;
use App\Ogniter\Model\Ogame\Highscore;
use App\Ogniter\Model\Ogame\Universe;
use App\Ogniter\Model\Ogame\UniverseMeta;
use App\Ogniter\Tools\File\CsvBuilder;
use App\Ogniter\Tools\Sql\Query\LoadData;
use Illuminate\Console\Command;

use App\Ogniter\Model\Ogame\Update;

use App\Ogniter\Model\Ogame\UniverseQueue as UniverseQueueModel;
use App\Ogniter\Api\Remote\Ogame\Task\Process\UniverseUpdateTask;

use App\Ogniter\Tools\Timer\TimerBag;

class InstallUniverseQueue extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ogame:install-universe-queue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Installs new Api-enabled Ogame Universes. One at a time';

    public function handle(
        UniverseUpdateTask $task,
        AllianceUpdateTask $allianceTask,
        PlayerUpdateTask $playerTask,
        PlanetUpdateTask $galaxyTask,
        UniverseQueueModel $universeQueueModel,
        Update $updateModel,
        TimerBag $timer)
    {
        $queue = $universeQueueModel->where('processed','=',0)->first();
        if(!$queue){
            $this->comment('All universes were installed!'.PHP_EOL);
            return;
        }

        \ini_set('memory_limit', '1024M');

        $this->comment("Starting Universe Queue Installer");
        try {
            /* Install Universe */
            //TODO: weight calculator
            $task
                ->setCountryId($queue->country_id)
                ->setWeight(0)
                ->setSpecial(0)
                ->setLocalName(\Config::get('ogame_servers.temporal_name'))
                ->setDomain($queue->domain);
            $task_id = $task->getTaskId();
            $timer->addTask($task_id);
            $task->run();
            $universe_id = $task->getUniverseId();

            //Do not request the same XML resource again
            $update = $updateModel->newIfNotAvailable($universe_id,Update::UPDATE_UNIVERSE);
            $update->last_update = time();
            $update->save();

            $updateModel->initUniverseUpdateRecords($universe_id);
            sleep(2);

            //Do not use the country 'language' or slug
            $universe = Universe::select('language')->where('id','=', $universe_id)->first();

            /* Install Highscore Table. If one of the following requests fail, we just shrug it */
            $highscore_sql = Universe::createHighscoreTableSQL($universe->language, $universe_id);
            \DB::statement($highscore_sql);

            /* Install Players */
            $update = $updateModel->newIfNotAvailable($universe_id,Update::UPDATE_PLAYER);
            $playerTask->setUniverseId($universe_id)
                ->setDomain($queue->domain)
                ->setLanguage($universe->language)
                ->setUpdateModel($update);
            $playerTask->run();

            sleep(2);

            /* Install Alliances */
            $update = $updateModel->newIfNotAvailable($universe_id,Update::UPDATE_ALLIANCE);
            $allianceTask->setUniverseId($universe_id)
                ->setDomain($queue->domain)
                ->setLanguage($universe->language)
                ->setUpdateModel($update);
            $allianceTask->run();

            sleep(2);

            /* Install Planets */
            $update = $updateModel->newIfNotAvailable($universe_id,Update::UPDATE_PLANET);
            $galaxyTask->setUniverseId($universe_id)
                ->setDomain($queue->domain)
                ->setLanguage($universe->language)
                ->setUpdateModel($update);
            $galaxyTask->run();

            sleep(2);

            gc_collect_cycles();

            /* Install Highscore */
            $time = time();
            $update = $updateModel->newIfNotAvailable($universe_id,Update::UPDATE_RANKING_HISTORY);
            for($c=1;$c<3;$c++){
                for($t=0;$t<8;$t++){
                    $u = $updateModel->newIfNotAvailable($universe_id,Update::UPDATE_RANKING, $c, $t);
                    
                    $highscoreTask = new HighscoreUpdateTask(
                        new DataBuilder(new CsvBuilder(), new LoadData()),
                        new DataBuilder(new CsvBuilder(), new LoadData()),
                        new DataBuilder(new CsvBuilder(), new LoadData()),
                        new HighscoreRequest());
                    $highscoreTask
                        ->setUpdateModel($u)
                        ->setUniverseId($universe_id)
                        ->setCategory($c)
                        ->setLanguage($universe->language)
                        ->setTimestamp($time)
                        ->setType($t)
                        ->setDomain($queue->domain);
                    $highscoreTask->run();

                    if($c!=2&&$t!=7) sleep(2);
                }
            }

            $tbl_name = Universe::getHighscoreTableName($universe->language, $universe_id);
            //We dump the collected data to the rankings table
            Highscore::replaceRankings($tbl_name, $universe_id, $time, \FALSE);

            $meta = UniverseMeta::where('universe_id','=', $universe_id)->first();
            $meta->last_global_update = $time; //is this still needed??
            $meta->save();

            $update->last_update = $time;
            $update->save();

            $queue->processed = 1;
            $queue->save();

        } catch( \Exception $e ){
            //In theory this needs a rollback, but Ogniter has worked this way for 3 years
            $this->error("Universe Install Error: ".$e->getMessage());
        } finally {
            if(!empty($task_id)){
                $timer->stopTask($task_id);
                $duration = $timer->getItem($task_id);

                $this->comment("Installation of ".$queue->domain." took ".$duration->getDifference()."s");
            } else {
                $this->error("Installation of ".$queue->domain." failed");
            }
        }

    }

}