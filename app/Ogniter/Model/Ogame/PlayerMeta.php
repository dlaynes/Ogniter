<?php
namespace App\Ogniter\Model\Ogame;

use App\Ogniter\Model\BaseModel;
use DB;

class PlayerMeta extends BaseModel
{
    protected $table = 'players_meta';

    function getVisits($universe_id, $player_id){
        $universe_id = (int) $universe_id;
        $player_id = (int) $player_id;

        $row = DB::selectOne("SELECT views FROM players_meta WHERE player_id=$player_id AND universe_id=$universe_id");

        return isset($row->views) ? $row->views : 0;
    }

    function addVisit($universe_id, $player_id){
        $universe_id = (int) $universe_id;
        $player_id = (int) $player_id;

        $sql = "INSERT INTO players_meta(`universe_id`,`player_id`,`views`)
                    VALUES ($universe_id, $player_id, 1)
                ON DUPLICATE KEY UPDATE views=views+1";
        DB::statement($sql);
    }

}