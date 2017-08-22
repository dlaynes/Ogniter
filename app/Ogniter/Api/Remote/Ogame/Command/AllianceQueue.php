<?php

namespace App\Ogniter\Api\Remote\Ogame\Command;

use App\Ogniter\Api\Remote\Ogame\Helper\DataBuilder;
use App\Ogniter\Api\Remote\Ogame\Task\Process\AllianceUpdateTask;
use App\Ogniter\Api\Remote\Ogame\Task\Request\AllianceRequest;
use App\Ogniter\Model\Ogame\Update;
use App\Ogniter\Tools\File\CsvBuilder;
use App\Ogniter\Tools\Sql\Query\LoadData;
use App\Ogniter\Tools\Timer\TimerBag;
use Illuminate\Console\Command;

class AllianceQueue extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ogame:alliance-queue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Loads pending info from alliances of Api-enabled Ogame Universes';

    public function handle(Update $updateModel, TimerBag $timer)
    {
        \ini_set('memory_limit', '128M');

        $has_errors = \FALSE;
        $updates = $updateModel->getUniverseForUpdate(Update::UPDATE_ALLIANCE, 10);
        $c = 1; $l = count($updates);

        if(!$l){
            $this->comment("Alliance queue task ended. No pending updates for now".PHP_EOL);
            return;
        }
        foreach($updates as $update){

            $task = new AllianceUpdateTask(
                new DataBuilder(new CsvBuilder(), new LoadData()),
                new DataBuilder(new CsvBuilder(), new LoadData()),
                new DataBuilder(new CsvBuilder(), new LoadData()),
                new AllianceRequest());

            $task->setUniverseId($update->universe_id)
                ->setDomain($update->domain)
                ->setLanguage($update->language)
                ->setUpdateModel($update);

            try {
                $task_id = $task->getTaskId();
                $timer->addTask($task_id);
                $task->run();
            } catch(\Exception $e){

                $this->error("Alliance Update Error: ".$e->getMessage());
                $has_errors = \TRUE;
            } finally {
                if(!empty($task_id)){
                    $timer->stopTask($task_id);
                    $duration = $timer->getItem($task_id);
                    $this->comment("Alliances update in universe #".$update->universe_id." (".$update->domain.") took ".$duration->getDifference()."s");
                } else {
                    $this->error("Alliance update of ".$update->domain." failed");
                }
                //gc_collect_cycles();

                if($has_errors){
                    return;
                }
                $c++;
                if($c<$l){
                    sleep(2);
                }
            }
        }
    }

}