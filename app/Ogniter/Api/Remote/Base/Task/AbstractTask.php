<?php

namespace App\Ogniter\Api\Remote\Base\Task;

/**
 * Class AbstractTask
 * @package App\Ogniter\Api\Remote\Base\Task
 */
abstract class AbstractTask {

    /**
     * @var string
     */
    protected $_tid = NULL;

    /**
     * Based on the given parameters, (and the current namespace?) child classes should provide
     * a special string which represents the current task.
     *
     * @return string
     */
    public abstract function buildTaskId();

    /**
     * Child classes will implement their behavior here.
     * Optionally, they will append info and error messages using ::appendInfoMessage()
     * and ::appendErrorMessage()
     *
     * @return null
     */
    protected abstract function processTask($result);

    /**
     * @return null
     */
    protected abstract function initTask();

    /**
     * @return null
     */
    protected abstract function closeTask();

    /**
     * Public interface for running the current task
     *
     * @return null
     */
    public abstract function run();

    /**
     * @return string
     */
    public function getTaskId(){
        return $this->_tid;
    }

    /**
     * @param $tid
     * @return null
     */
    public function setTaskId($tid){
        $this->_tid = $tid;
    }


}