<?php

namespace App\Ogniter\Api\Remote\Ogame\Task\Process;

use App\Ogniter\Api\Remote\Ogame\Helper\DataBuilder;
use App\Ogniter\Api\Remote\Ogame\Task\Request\HighscoreRequest;
use App\Ogniter\Model\Ogame\Highscore;
use App\Ogniter\Model\Ogame\Universe;
use App\Ogniter\Model\Ogame\HighscoreLog;

class HighscoreUpdateTask extends ProcessRequestTask {

    use UsesUniverseInfo;

    protected $_category = \NULL;

    protected $_type = \NULL;

    protected $_lastUpdate = 0;

    protected $_dataHighscore = \NULL;

    protected $_dataRankingHistory = \NULL;

    protected $_dataRankings = \NULL;

    protected $_timestamp = \NULL;

    protected $_domain = '';

    function __construct(
        DataBuilder $highscoreData,
        DataBuilder $rankingHistoryData,
        DataBuilder $rankingData,
        HighscoreRequest $request)
    {
        $this->_dataHighscore = $highscoreData;
        $this->_dataRankingHistory = $rankingHistoryData;
        $this->_dataRankings = $rankingData;
        $this->_request = $request;
    }

    public function setTimestamp($timestamp){
        $this->_timestamp = (int) $timestamp;
        return $this;
    }

    public function setCategory($cat){
        $this->_category = (int) $cat;
        return $this;
    }

    public function setType($type){
        $this->_type = (int) $type;
        return $this;
    }

    public function setDomain($domain){
        $this->_domain = $domain;
        return $this;
    }

    public function buildUrl()
    {
        return 'https://'.$this->_domain.'/api/highscore.xml?category='.$this->_category.'&type='.$this->_type;
    }

    public function validateParams()
    {
        if(empty($this->_timestamp)){
            throw new \Exception("Highscore update - You must set the base timestamp");
        }
        if(empty($this->_domain)){
            throw new \Exception("Highscore update - You must set a domain");
        }
        if(!in_array($this->_category, [1,2])){
            throw new \Exception("Highscore update - Invalid category");
        }
        if(!in_array($this->_type, [0,1,2,3,4,5,6,7])){
            throw new \Exception("Highscore update - Invalid type");
        }

        $this->validateUniverseData();
        //All is OK
    }

    public function processTask($highscore)
    {
        //Put these in order, based on your table!!
        $fields = ['`entity_id`','`type`','`category`','`last_update`','`position`','`score`','`ships`'];
        $file_name = storage_path('ogame_highscore_'.$this->_category.'_'.$this->_type.'_from_'.$this->_universeId.'.csv');

        $tbl_name = Universe::getHighscoreTableName($this->_language, $this->_universeId);
        $this->_dataHighscore
            ->setTableName($tbl_name)
            ->setFields($fields)
            ->setFile($file_name)
            ->setReplace(\TRUE);

        /*
        $fields_history = ['`entity_id`','`type`','`category`','`last_update`','`position`','`score`','`ships`'];
        $file_name_history = storage_path('ogame_ranking_history_'.$this->_category.'_'.$this->_type.'_from_'.$this->_universeId.'.csv');
        $this->_dataRankingHistory
            ->setTableName($tbl_name.'_innodb')
            ->setFields($fields_history)
            ->setFile($file_name_history);
        */

        $fields_rankings = ['`universe_id`','`entity_id`','`type`','`category`','`last_update`','`position`','`score`','`ships`','`active`'];
        $file_name_rankings = storage_path('ogame_rankings_'.$this->_category.'_'.$this->_type.'_from_'.$this->_universeId.'.csv');
        $this->_dataRankings
            ->setTableName('rankings')
            ->setFields($fields_rankings)
            ->setReplace(\TRUE)
            ->setFile($file_name_rankings);

        //TODO: we need to reset the previous stats

        foreach($highscore['highscore'] as $r){
            $record = [
                $r->entity_id,
                $this->_type,
                $this->_category,
                $this->_timestamp,
                $r->position,
                $r->score,
                $r->ships
            ];
            $this->_dataHighscore->addRow($record);

            \array_unshift($record, $this->_universeId);
            //$this->_dataRankingHistory->addRow($record);
            $record[] = 1;
            $this->_dataRankings->addRow($record);
        }

        Highscore::resetRankingsByGroup($this->_universeId, $this->_category, $this->_type);

        $this->_dataHighscore->save();
        //$this->_dataRankingHistory->save();
        $this->_dataRankings->save();

        $log = new HighscoreLog();
        $log->saveEntry($this->_universeId, $this->_type, $this->_category, $this->_timestamp);
    }

    public function buildTaskId()
    {
        return 'task-highscore-'.$this->_universeId.'-'.$this->_category.'-'.$this->_type;
    }

    public function closeTask()
    {
        //Timestamp instead of $_lastUpdate. Hmmm
        if(!empty($this->_updateModel)){
            $this->_updateModel->last_update = (int) $this->_timestamp;
        }
        return parent::closeTask();
    }

}