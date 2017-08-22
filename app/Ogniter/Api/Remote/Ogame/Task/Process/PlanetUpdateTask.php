<?php

namespace App\Ogniter\Api\Remote\Ogame\Task\Process;

use App\Ogniter\Api\Remote\Ogame\Helper\DataBuilder;
use App\Ogniter\Api\Remote\Ogame\Task\Request\PlanetRequest;
use App\Ogniter\Model\Ogame\Planet;
use App\Ogniter\Model\Ogame\PlanetChanges;

class PlanetUpdateTask extends ProcessRequestTask {

    use UsesUniverseInfo;

    protected $_lastUpdate = 0;

    protected $_dataPlanets = NULL;

    protected $_dataPlanetChanges = NULL;

    protected $_planetModel = NULL;

    protected $_domain = '';

    function __construct(DataBuilder $planetsData, DataBuilder $planetChanges,
                         PlanetRequest $request)
    {
        $this->_planetModel = new Planet();
        $this->_dataPlanets = $planetsData;
        $this->_dataPlanetChanges = $planetChanges;
        $this->_request = $request;
    }

    public function setDomain($domain){
        $this->_domain = $domain;
        return $this;
    }

    public function buildUrl()
    {
        return 'https://'.$this->_domain.'/api/universe.xml';
    }

    public function validateParams()
    {
        if(empty($this->_domain)){
            throw new \Exception("Planet update - You must set a domain");
        }
        $this->validateUniverseData();
        //All is OK
    }

    public function processTask($planets)
    {
        $planetChangesModel = new PlanetChanges();

        $last_update = $planets['last_update'];

        //Put these in order, based on your table!!
        $fields = ['`planet_id`','`universe_id`','`player_id`','`name`','`galaxy`','`system`','`position`',
            '`type`','`size`','`last_update`','`active`'];
        $file_name = storage_path('ogame_planets_from_'.$this->_universeId.'.csv');
        $this->_dataPlanets
            ->setTableName('planets')
            ->setFields($fields)
            ->setFile($file_name)
            ->setReplace(\TRUE);

        $fields_changes = ['`planet_id`','`universe_id`','`name`','`gal`','`sys`','`pos`','`modified_on`'];
        $file_name_changes = storage_path('ogame_planet_changes_from_'.$this->_universeId.'.csv');
        $this->_dataPlanetChanges
            ->setTableName('planet_changes')
            ->setFields($fields_changes)
            ->setFile($file_name_changes);

        foreach($planets['planets'] as $p){
            $name = $p->name ? '"'.str_replace('"','\"',$p->name).'"' : '';

            $record = [
                $p->planet_id,
                $this->_universeId,
                $p->player_id,
                $name,
                $p->galaxy,
                $p->system,
                $p->position,
                $p->type,
                $p->size,
                $last_update,
                1
            ];
            $this->_dataPlanets->addRow($record);

            $changes = $planetChangesModel->getLastChange($this->_universeId, $p->planet_id);
            if($changes && $changes->name==$p->name
                && $changes->gal==$p->galaxy
                && $changes->sys==$p->system
                && $changes->pos==$p->position){
                continue;
            }
            $changes = NULL;
            gc_collect_cycles();

            $record = [
                $p->planet_id,
                $this->_universeId,
                $name,
                $p->galaxy,
                $p->system,
                $p->position,
                $last_update,
            ];
            $this->_dataPlanetChanges->addRow($record);
        }

        $this->_planetModel->softDeleteInUniverse($this->_universeId);

        $this->_dataPlanets->save();
        $this->_dataPlanetChanges->save();

        $this->_lastUpdate = $last_update;
    }

    public function buildTaskId()
    {
        return 'task-planet-'.$this->_universeId;
    }

    public function closeTask()
    {
        if(!empty($this->_updateModel)){
            $this->_updateModel->last_update = (int) $this->_lastUpdate;
        }
        return parent::closeTask();
    }

}