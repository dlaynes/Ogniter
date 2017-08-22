<?php

namespace App\Ogniter\Api\Remote\Ogame\Command;

use App\Ogniter\Api\Remote\Ogame\Helper\DataBuilder;
use App\Ogniter\Api\Remote\Ogame\Task\Process\PlanetUpdateTask;
use App\Ogniter\Api\Remote\Ogame\Task\Request\PlanetRequest;
use App\Ogniter\Model\Ogame\Update;
use App\Ogniter\Tools\File\CsvBuilder;
use App\Ogniter\Tools\Sql\Query\LoadData;
use App\Ogniter\Tools\Timer\TimerBag;
use Illuminate\Console\Command;

class GalaxyQueue extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ogame:galaxy-queue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Loads pending info from planets of Api-enabled Ogame Universes';

    public function handle(Update $updateModel, TimerBag $timer)
    {
        \ini_set('memory_limit', '1024M');

        $has_errors = \FALSE;

        $updates = $updateModel->getUniverseForUpdate(Update::UPDATE_PLANET, 5);
        $c = 1; $l = count($updates);

        if(!$l){
            $this->comment("Planet queue task ended. No pending updates for now".PHP_EOL);
            return;
        }

        foreach($updates as $update){
            //Rip Dependency injection
            $task = new PlanetUpdateTask(
                new DataBuilder(new CsvBuilder(), new LoadData()),
                new DataBuilder(new CsvBuilder(), new LoadData()),
                new PlanetRequest());

            $task->setUniverseId($update->universe_id)
                ->setDomain($update->domain)
                ->setLanguage($update->language)
                ->setUpdateModel($update);

            try {
                $task_id = $task->getTaskId();
                $timer->addTask($task_id);
                $task->run();
            } catch(\Exception $e){
                $this->error("Planet Update Error: ".$e->getMessage());
                $has_errors = \TRUE;
            } finally {
                if(!empty($task_id)){
                    $timer->stopTask($task_id);
                    $duration = $timer->getItem($task_id);
                    $this->comment("Planet update in universe #".$update->universe_id." (".$update->domain.") took ".
                        $duration->getDifference()."s");
                } else {
                    $this->error("Planet update of ".$update->domain." failed");
                }

                if($has_errors){
                    return;
                }

                $c++;
                if($c<$l){
                    sleep(2);
                }
                //gc_collect_cycles();
            }
        }
    }

}