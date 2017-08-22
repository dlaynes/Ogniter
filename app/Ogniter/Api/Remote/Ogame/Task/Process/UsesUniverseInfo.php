<?php

namespace App\Ogniter\Api\Remote\Ogame\Task\Process;

trait UsesUniverseInfo {
    
    protected $_universeId;

    protected $_language;

    public function setUniverseId($universeId){
        $this->_universeId = (int) $universeId;
        return $this;
    }

    public function setLanguage($language){
        $this->_language = $language;
        return $this;
    }

    function validateUniverseData(){
        if(empty($this->_universeId)){
            throw new \Exception("You must set an universe ID");
        }
        if(empty($this->_language)){
            throw new \Exception("You must set a base language");
        }
    }

}