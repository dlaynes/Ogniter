<?php

namespace App\Ogniter\Api\Remote\Ogame\Task\Process;

use App\Ogniter\Api\Remote\Ogame\Helper\DataBuilder;
use App\Ogniter\Api\Remote\Ogame\Task\Request\AllianceRequest;
use App\Ogniter\Model\Ogame\Alliance;
use App\Ogniter\Model\Ogame\AllianceChanges;

class AllianceUpdateTask extends ProcessRequestTask {

    use UsesUniverseInfo;

    protected $_lastUpdate = 0;

    protected $_dataAlliances = NULL;

    protected $_dataAllianceChanges = NULL;

    protected $_dataAllianceMeta = NULL;

    protected $_domain = '';

    function __construct(DataBuilder $alliancesData, DataBuilder $allianceChanges,
                         DataBuilder $alliancesMeta, AllianceRequest $request)
    {
        $this->_dataAlliances = $alliancesData;
        $this->_dataAllianceChanges = $allianceChanges;
        $this->_dataAllianceMeta = $alliancesMeta;
        $this->_request = $request;
    }

    public function setDomain($domain){
        $this->_domain = $domain;
        return $this;
    }

    public function buildUrl()
    {
        return 'https://'.$this->_domain.'/api/alliances.xml';
    }

    public function validateParams()
    {
        if(empty($this->_domain)){
            throw new \Exception("Alliance update - You must set a domain");
        }
        $this->validateUniverseData();
        //All is OK
    }

    public function processTask($alliances)
    {
        $allianceChangesModel = new AllianceChanges();

        $last_update = $alliances['last_update'];

        //Put these in order, based on your table!!
        $fields = ['`alliance_id`','`universe_id`','`name`','`tag`','`homepage`','`logo`','`open`','`last_update`','`active`'];
        $file_name = storage_path('ogame_alliances_from_'.$this->_universeId.'.csv');
        $this->_dataAlliances
            ->setTableName('alliances')
            ->setFields($fields)
            ->setFile($file_name)
            ->setReplace(\TRUE);

        $fields_changes = ['`alliance_id`','`universe_id`','`name`','`tag`','`open`','`modified_on`'];
        $file_name_changes = storage_path('ogame_alliance_changes_from_'.$this->_universeId.'.csv');
        $this->_dataAllianceChanges
            ->setTableName('alliance_changes')
            ->setFields($fields_changes)
            ->setFile($file_name_changes);

        //Yes, it is named alliance meta. TODO: fix db name errors
        $fields_meta = ['`alliance_id`','`universe_id`','`views`'];
        $file_name_meta = storage_path('ogame_alliance_meta_from_'.$this->_universeId.'.csv');
        $this->_dataAllianceMeta
            ->setTableName('alliance_meta')
            ->setIgnore(\TRUE) //Don't replace old stuff!!
            ->setFields($fields_meta)
            ->setFile($file_name_meta);

        foreach($alliances['alliances'] as $a){
            $name = $a->name ? '"'.str_replace('"','\"',$a->name).'"' : '';
            $tag = $a->tag ? '"'.str_replace('"','\"',$a->tag).'"' : '';
            $open = (int) $a->open;

            $record = [
                $a->alliance_id,
                $this->_universeId,
                $name,
                $tag,
                $a->homepage ? '"'.str_replace('"','\"',$a->homepage).'"' : '',
                $a->logo ? '"'.str_replace('"','\"',$a->logo).'"' : '',
                $open,
                $last_update,
                1
            ];
            $this->_dataAlliances->addRow($record);

            //if it already exists, it will be ignored
            $record = [
                $a->alliance_id,
                $this->_universeId,
                0
            ];
            $this->_dataAllianceMeta->addRow($record);

            //There is no other way, we must loop all records
            $changes = $allianceChangesModel->getLastChange($this->_universeId, $a->alliance_id);
            if($changes && $changes->name==$a->name
                && $changes->tag==$a->tag
                && $changes->open==$open){
                continue;
            }
            $changes = NULL;
            gc_collect_cycles();

            $record = [
                $a->alliance_id,
                $this->_universeId,
                $name,
                $tag,
                (int) $a->open,
                $last_update
            ];
            $this->_dataAllianceChanges->addRow($record);
        }
        
        $alliance = new Alliance();
        $alliance->softDeleteInUniverse($this->_universeId);

        $this->_dataAlliances->save();
        $this->_dataAllianceChanges->save();
        $this->_dataAllianceMeta->save();

        $this->_lastUpdate = $last_update;
    }

    public function buildTaskId()
    {
        return 'task-alliance-'.$this->_universeId;
    }

    public function closeTask()
    {
        if(!empty($this->_updateModel)){
            $this->_updateModel->last_update = (int) $this->_lastUpdate;
        }
        return parent::closeTask();
    }

}