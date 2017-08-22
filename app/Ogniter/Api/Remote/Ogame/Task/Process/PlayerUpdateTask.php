<?php

namespace App\Ogniter\Api\Remote\Ogame\Task\Process;

use App\Ogniter\Api\Remote\Ogame\Helper\DataBuilder;
use App\Ogniter\Api\Remote\Ogame\Task\Request\PlayerRequest;
use App\Ogniter\Model\Ogame\BannedUsers;
use App\Ogniter\Model\Ogame\PlayerChanges;
use App\Ogniter\Model\Ogame\Player;

class PlayerUpdateTask extends ProcessRequestTask {

    use UsesUniverseInfo;

    protected $_lastUpdate = 0;

    protected $_dataPlayers = NULL;

    protected $_dataPlayerChanges = NULL;

    protected $_dataPlayersMeta = NULL;

    protected $_domain = '';

    function __construct(DataBuilder $playersData, DataBuilder $playerChanges,
                         DataBuilder $playersMeta, PlayerRequest $request)
    {
        $this->_dataPlayers = $playersData;
        $this->_dataPlayerChanges = $playerChanges;
        $this->_dataPlayersMeta = $playersMeta;
        $this->_request = $request;
    }

    public function setDomain($domain){
        $this->_domain = $domain;
        return $this;
    }

    public function buildUrl()
    {
        return 'https://'.$this->_domain.'/api/players.xml';
    }

    public function validateParams()
    {
        if(empty($this->_domain)){
            throw new \Exception("Player update - You must set a domain");
        }
        $this->validateUniverseData();
        //All is OK
    }

    public function processTask($players)
    {
        $playerChangesModel = new PlayerChanges();

        $last_update = $players['last_update'];

        //Put these in order, based on your table!!
        $fields = ['`player_id`','`universe_id`','`alliance_id`','`name`','`status`','`last_update`','`active`'];
        $file_name = storage_path('ogame_players_from_'.$this->_universeId.'.csv');
        $this->_dataPlayers
            ->setTableName('players')
            ->setFields($fields)
            ->setFile($file_name)
            ->setReplace(\TRUE);

        $fields_changes = ['`player_id`','`universe_id`','`name`','`alliance_id`','`status`','`modified_on`'];
        $file_name_changes = storage_path('ogame_player_changes_from_'.$this->_universeId.'.csv');
        $this->_dataPlayerChanges
            ->setTableName('player_changes')
            ->setFields($fields_changes)
            ->setFile($file_name_changes);

        $fields_meta = ['`player_id`','`universe_id`','`views`'];
        $file_name_meta = storage_path('ogame_player_meta_from_'.$this->_universeId.'.csv');
        $this->_dataPlayersMeta
            ->setTableName('players_meta')
            ->setIgnore(\TRUE) //Don't replace old stuff!!
            ->setFields($fields_meta)
            ->setFile($file_name_meta);

        foreach($players['players'] as $p){

            $status = Player::statusToNumber($p->raw_status);
            $name = $p->name ? '"'.str_replace('"','\"',$p->name).'"' : '';

            $record = [
                $p->player_id,
                $this->_universeId,
                $p->alliance_id,
                $name,
                $status,
                $last_update,
                1
            ];
            $this->_dataPlayers->addRow($record);

            $record = [
                $p->player_id,
                $this->_universeId,
                0
            ];
            $this->_dataPlayersMeta->addRow($record);

            //Ughh. Actually, it doesn't take too long
            $changes = $playerChangesModel->getLastChange($this->_universeId, $p->player_id);
            if($changes){
                if(!($changes->status & Player::STATUS_BANNED) && ($status & Player::STATUS_BANNED) ){
                    BannedUsers::insert([
                        'universe_id' => $this->_universeId,
                        'player_id' => $p->player_id,
                        'added_on' => $last_update,
                        'removed_on' => 0
                    ]);
                } elseif(($changes->status & Player::STATUS_BANNED) && !($status & Player::STATUS_BANNED)){
                    $last = BannedUsers::getLastBanFromUser($this->_universeId, $p->player_id);
                    if($last){
                        $last->removed_on = $last_update;
                        $last->save();
                    }
                }
                if($changes->name==$p->name
                    && $status==$changes->status
                    && $changes->alliance_id==$p->alliance_id){
                    continue;
                }
                $changes = NULL;
            }
            gc_collect_cycles();

            $record = [
                $p->player_id,
                $this->_universeId,
                $name,
                $p->alliance_id,
                $status,
                $last_update
            ];
            $this->_dataPlayerChanges->addRow($record);
        }

        $player = new Player();
        $player->softDeleteInUniverse($this->_universeId);

        $this->_dataPlayerChanges->save();
        $this->_dataPlayers->save();
        $this->_dataPlayersMeta->save();

        $this->_lastUpdate = $last_update;
    }

    public function buildTaskId()
    {
        return 'task-player-'.$this->_universeId;
    }

    public function closeTask()
    {
        if(!empty($this->_updateModel)){
            $this->_updateModel->last_update = (int) $this->_lastUpdate;
        }
        return parent::closeTask();
    }

}