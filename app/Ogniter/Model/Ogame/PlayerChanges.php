<?php
namespace App\Ogniter\Model\Ogame;

use App\Ogniter\Model\BaseModel;
use DB;

class PlayerChanges extends BaseModel
{
    function getLastChange($universe_id, $player_id){
        $universe_id = (int) $universe_id;
        $player_id = (int) $player_id;
        return DB::selectOne("SELECT pc.name, pc.alliance_id, pc.status FROM `player_changes` AS pc
            WHERE pc.universe_id={$universe_id} AND pc.player_id={$player_id}
        	ORDER BY modified_on DESC limit 1");
    }

    function getChangesFrom($universe_id, $player_id){
        $universe_id = (int) $universe_id;
        $player_id = (int) $player_id;

        return DB::select("SELECT pc.name, pc.alliance_id, a.name AS alliance_name, pc.status, pc.modified_on
            FROM player_changes as pc
            LEFT JOIN alliances as a ON a.alliance_id=pc.alliance_id AND a.universe_id=$universe_id
            WHERE pc.player_id=$player_id AND pc.universe_id=$universe_id
            ORDER BY modified_on ASC");
    }

}