<?php

namespace App\Ogniter\Tools\Timer;

class TimerItem{

    protected $id;

    protected $startedOn;

    protected $finishedOn;

    function __construct($id)
    {
        $this->id = $id;
        //this->start()
        $this->startedOn = microtime(TRUE);
    }

    public function stop(){
        $this->finishedOn = microtime(TRUE);
    }

    public function getDifference(){
        return $this->finishedOn - $this->startedOn;
    }

    public function getId(){
        return $this->id;
    }

}