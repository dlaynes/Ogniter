<?php

namespace App\Ogniter\Maintenance\Task;


use App\Ogniter\Maintenance\Base\MaintenanceTask;

class TopFlopTask extends MaintenanceTask {

    protected $_universeId;

    public function setUniverseId($universe_id){
        $this->_universeId = $universe_id;
        return $this;
    }

    public function processTask($result)
    {
        //Pranger generation goes here
    }

    public function buildTaskId()
    {
        return 'task-top-flop-'.$this->_universeId;
    }

    public function validateParams()
    {
        if(empty($this->_universeId)){
            throw new \Exception("Top flop task - Define an universe ID");
        }
    }

}