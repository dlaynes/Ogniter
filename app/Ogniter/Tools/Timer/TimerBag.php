<?php

namespace App\Ogniter\Tools\Timer;

class TimerBag {

    protected $items = array();

    public function addTask($id){
        if(!isset($this->items[$id])){
            $this->items[$id] = new TimerItem($id);
        } else {
            throw new \Exception("Task $id already started");
        }
    }

    public function stopTask($id){
        if(isset($this->items[$id])){
            $this->items[$id]->stop();
        } else {
            throw new \Exception("Task $id not found");
        }
    }

    public function getTasks(){
        return $this->items;
    }

    public function getItem($id){
        if(isset($this->items[$id])){
            return $this->items[$id];
        } else {
            throw new \Exception("Task $id not found");
        }
    }

    public static function prettyNanoTime($nanoseconds){
        return '';
    }

}