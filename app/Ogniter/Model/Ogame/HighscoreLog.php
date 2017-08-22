<?php
namespace App\Ogniter\Model\Ogame;

use App\Ogniter\Model\BaseModel;
use DB;

class HighscoreLog extends BaseModel {

    protected $table = "ranking_updates";

    protected $fillable = ['universe_id', 'updated_on', 'category', 'type' ];

    public $timestamps = false;

    public function saveEntry($universe_id, $type, $category, $timestamp){
        $sql = "REPLACE INTO ranking_updates (`universe_id`,`type`,`category`, `updated_on`)
                VALUES ($universe_id, $type, $category, $timestamp)";
        DB::statement($sql);
    }

    static function getLatestRankingUpdate($universe_id, $category, $type){
        $universe_id = (int) $universe_id;
        $category = (int) $category;
        $type = (int) $type;

        $sql = "SELECT MAX(updated_on) AS max FROM ranking_updates
			WHERE universe_id=$universe_id AND category=$category AND type=$type";
        $row = DB::selectOne($sql);
        return isset($row->max) ? $row->max : NULL;
    }

    static function getPreviousRankingUpdate($universe_id, $before_this, $category, $type){
        $sql = "SELECT updated_on FROM ranking_updates
			WHERE universe_id=$universe_id AND category=$category AND type=$type
			AND updated_on < $before_this
			ORDER BY updated_on DESC LIMIT 1";

        $row = DB::selectOne($sql);
        return isset($row->updated_on) ? $row->updated_on : NULL;
    }

    static function getRankingUpdateNear($universe_id, $after_time, $category, $type){
        $universe_id = (int) $universe_id;
        $after_time = (int) $after_time;
        $category = (int) $category;
        $type = (int) $type;

        $sql = "SELECT updated_on FROM ranking_updates
			WHERE universe_id=$universe_id AND category=$category AND type=$type
			AND updated_on > $after_time
			ORDER BY updated_on LIMIT 1";

        $row = DB::selectOne($sql);
        return isset($row->updated_on) ? $row->updated_on : NULL;
    }

}