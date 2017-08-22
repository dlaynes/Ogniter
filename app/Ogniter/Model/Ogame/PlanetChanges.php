<?php
namespace App\Ogniter\Model\Ogame;

use App\Ogniter\Model\BaseModel;
use DB;

class PlanetChanges extends BaseModel
{
    function getLastChange($universe_id, $planet_id){
        $universe_id = (int) $universe_id;
        $planet_id = (int) $planet_id;
        return DB::selectOne("SELECT pc.name, pc.gal, pc.sys, pc.pos FROM `planet_changes` AS pc
            WHERE pc.universe_id={$universe_id} AND pc.planet_id={$planet_id}
        	ORDER BY modified_on DESC limit 1");
    }

    function getChangesFrom($universe_id, $planet_id){
        $universe_id = (int) $universe_id;
        $planet_id = (int) $planet_id;

        return DB::select("SELECT pc.name, pc.gal, pc.sys, pc.pos, pc.modified_on
            FROM planet_changes as pc
            WHERE pc.planet_id=$planet_id AND pc.universe_id=$universe_id
            ORDER BY modified_on ASC");
    }

}