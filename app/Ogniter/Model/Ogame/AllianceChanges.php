<?php
namespace App\Ogniter\Model\Ogame;

use App\Ogniter\Model\BaseModel;
use DB;

class AllianceChanges extends BaseModel
{

    protected $table = "alliance_changes";

    public $timestamps = false;

    function getLastChange($universe_id, $alliance_id){
        $universe_id = (int) $universe_id;
        $alliance_id = (int) $alliance_id;
        return DB::selectOne("SELECT ac.name, ac.tag, ac.open FROM `alliance_changes` AS ac
          WHERE ac.universe_id={$universe_id} AND ac.alliance_id={$alliance_id}
          ORDER BY modified_on DESC limit 1");
    }

    function getChangesFrom($universe_id, $alliance_id){
        $universe_id = (int) $universe_id;
        $alliance_id = (int) $alliance_id;

        return DB::select("SELECT ac.name, ac.tag, ac.open, ac.modified_on
            FROM alliance_changes as ac
            WHERE ac.alliance_id=$alliance_id AND ac.universe_id=$universe_id
            ORDER BY modified_on ASC");
    }

}