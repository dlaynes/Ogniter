<?php

namespace App\Ogniter\Maintenance\Task;


use App\Ogniter\Maintenance\Base\MaintenanceTask;

class DeleteInactiveHighscoreTask extends MaintenanceTask {

    protected $_universeId;

    public function setUniverseId($universe_id){
        $this->_universeId = $universe_id;
        return $this;
    }

    public function processTask($result)
    {
        //Historical Highscore Task defined here
    }

    public function buildTaskId()
    {
        return 'task-delete-inactive-highscore-'.$this->_universeId;
    }

    public function validateParams()
    {
        if(empty($this->_universeId)){
            throw new \Exception("Remove inactive players from Highscore Task - Define an universe ID");
        }
    }

}