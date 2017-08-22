<?php

namespace App\Ogniter\Model;

class HashTable {
    protected $records = [];

    public function getRecords(){
        return $this->records;
    }

    //TO DO
    public function getKeys(){

    }

    //TO DO
    public function getValues(){
        
    }

    public function existsByKey($code){
        return isset($this->records[$code]);
    }
    public function getByKey($code)
    {
        if(isset($this->records[$code])){
            return $this->records[$code];
        }
        return NULL;
    }
}
