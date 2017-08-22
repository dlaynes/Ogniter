<?php

namespace App\Ogniter\Maintenance\Base;

use App\Ogniter\Model\Ogame\Update;
use App\Ogniter\Api\Remote\Base\Task\AbstractTask;

/**
 * Class UpdateTask
 * @package App\Ogniter\Api\Ogame\Task\Process
 */
abstract class MaintenanceTask extends AbstractTask {

    /**
     * @var
     */
    protected $_updateModel;

    /* Set the base url of the resource */
    /**
     * @return mixed
     */
    public function buildUrl(){
        return \NULL;
    }

    public function getTaskId()
    {
        if(!$this->_tid){
            try{
                $this->validateParams();
            }  catch(\Exception $e ){
                throw $e;
            }
            $this->setTaskId( $this->buildTaskId() );
        }
        return parent::getTaskId();
    }

    /**
     * Before running the current Task, children classes should verify that all the parameters provided
     * have a valid format and value types, and return a boolean value
     * depending if those passed the tests or not.
     *
     * @return bool
     */
    protected abstract function validateParams();

    /**
     * @throws \Exception
     */
    public function run(){

        if(empty($this->getTaskId())){
            throw new \Exception("Task ID cannot be empty on ".get_class($this));
        }

        try {
            $this->initTask();
            $this->processTask(\NULL);

        } catch(\Exception $e){
            //If the resource is not available...
            if(!empty($this->_updateModel)){
                $this->_updateModel->last_update =
                    time() - Update::getExpirationDelay($this->_updateModel->update_type_id);
            }
            throw $e;

        } finally {
            $this->closeTask();
        }
    }

    /**
     * @param Update $updateModel
     * @return $this
     */
    public function setUpdateModel(Update $updateModel){
        $this->_updateModel = $updateModel;
        return $this;
    }

    /**
     *
     */
    protected function initTask(){
        if(empty($this->_updateModel)) return;

        $this->_updateModel->updating = 1;
        $this->_updateModel->updating_on = time();
        $this->_updateModel->save();
    }

    /**
     *
     */
    protected function closeTask(){
        if(empty($this->_updateModel)) return;

        $this->_updateModel->updating = 0;
        $this->_updateModel->save();
    }

}