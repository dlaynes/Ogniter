<?php

namespace App\Ogniter\Maintenance\Task;


use App\Ogniter\Maintenance\Base\MaintenanceTask;

class RecordsPlayerTask extends MaintenanceTask {

    protected $_universeId;

    public function setUniverseId($universe_id){
        $this->_universeId = $universe_id;
        return $this;
    }

    public function processTask($result)
    {
        //records player generation goes here
    }

    public function buildTaskId()
    {
        return 'task-records-player-'.$this->_universeId;
    }

    public function validateParams()
    {
        if(empty($this->_universeId)){
            throw new \Exception("Records Player task - Define an universe ID");
        }
    }

}