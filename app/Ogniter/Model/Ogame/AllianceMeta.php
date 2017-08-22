<?php
namespace App\Ogniter\Model\Ogame;

use App\Ogniter\Model\BaseModel;
use DB;

class AllianceMeta extends BaseModel
{

    protected $table = "alliance_meta";

    public $timestamps = false;

    function getVisits($universe_id, $alliance_id){
        $universe_id = (int) $universe_id;
        $alliance_id = (int) $alliance_id;

        $row = DB::selectOne("SELECT views FROM alliance_meta
          WHERE alliance_id=$alliance_id AND universe_id=$universe_id");
        return isset($row->views) ? $row->views : 0;
    }

    function addVisit($universe_id, $alliance_id){
        $universe_id = (int) $universe_id;
        $alliance_id = (int) $alliance_id;
        $sql = "INSERT INTO alliance_meta (`universe_id`,`alliance_id`,`views`)
                    VALUES ($universe_id, $alliance_id, 1)
                ON DUPLICATE KEY UPDATE views=views+1";
        DB::statement($sql);
    }

}