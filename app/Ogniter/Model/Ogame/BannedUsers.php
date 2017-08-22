<?php

namespace App\Ogniter\Model\Ogame;
use App\Ogniter\Model\BaseModel;
use DB;

class BannedUsers extends BaseModel {
    protected $table = "banned_users";

    public $timestamps = false;
    
    public static function getLastBanFromUser($universe_id, $player_id){
        return self::where('universe_id','=',$universe_id)
            ->where('player_id','=',$player_id)
            ->where('removed_on','=',0)
            ->orderBy('added_on','DESC')->first();
    }

    function count($universe_id){
        $universe_id = (int) $universe_id;

        $sql = "SELECT COUNT(*) AS c FROM banned_users WHERE universe_id=$universe_id";
        return DB::selectOne($sql)->c;
    }

    function getList($universe_id, $limit, $offset){
        $universe_id = (int) $universe_id;
        $limit = (int) $limit;
        $offset = (int) $offset;

        $sql = "SELECT b.player_id, p.name, r_0.position, b.added_on, b.removed_on, p.status
			FROM banned_users AS b
			JOIN players AS p ON p.universe_id=b.universe_id AND p.player_id=b.player_id
			LEFT JOIN rankings AS r_0
			ON r_0.entity_id=p.player_id AND r_0.universe_id=p.universe_id
			AND r_0.type=0 AND r_0.category=1
			WHERE b.universe_id={$universe_id} ORDER BY b.added_on DESC LIMIT {$limit} OFFSET {$offset}";

        return DB::select($sql);
    }
    
}