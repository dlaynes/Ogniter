<?php

namespace App\Ogniter\Api\Remote\Ogame\Command;

use App\Ogniter\Api\Remote\Ogame\Task\Request\UniverseRequest;
use Illuminate\Console\Command;

use App\Ogniter\Model\Ogame\Update;

use App\Ogniter\Api\Remote\Ogame\Task\Process\UniverseUpdateTask;

use App\Ogniter\Tools\Timer\TimerBag;

class UniverseQueue extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ogame:universe-queue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Loads pending detail info of Api-enabled Ogame Universes';

    public function handle(
        Update $updateModel,
        TimerBag $timer)
    {
        \ini_set('memory_limit', '32M');

        $has_errors = \FALSE;
        $updates = $updateModel->getUniverseForUpdate(Update::UPDATE_UNIVERSE, 10);

        $c = 1; $l = count($updates);

        if(!$l){
            $this->comment("Universe queue task ended. No pending updates for now".PHP_EOL);
            return;
        }
        foreach($updates as $update){

            $task = new UniverseUpdateTask(new UniverseRequest());

            $task->setWeight($update->weight)
                ->setDomain($update->domain)
                ->setLocalName($update->local_name)
                ->setUpdateModel($update);

            try {
                $task_id = $task->getTaskId();
                $timer->addTask($task_id);
                $task->run();
                $universe_id = $task->getUniverseId();
            } catch(\Exception $e){
                $this->error("Universe Update Error: ".$e->getMessage());
                $has_errors = \TRUE;
            } finally {
                if(!empty($task_id) && !empty($universe_id)){
                    $timer->stopTask($task_id);
                    $duration = $timer->getItem($task_id);
                    $this->comment("Update of universe #".$universe_id." (".$update->domain.") took ".$duration->getDifference()."s");
                } else {
                    $this->error("Update of ".$update->domain." failed");
                }

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