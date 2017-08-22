<?php
namespace App\Ogniter\Model\Ogame;

use App\Ogniter\Model\BaseModel;
use App\Ogniter\Tools\Sql\Query\Security;
use DB;

class Player extends BaseModel {

    protected $table = "players";

    public $timestamps = false;

    const STATUS_INACTIVE = 1;
    const STATUS_30_INACTIVE = 2;
    const STATUS_VACATION = 4;
    const STATUS_BANNED = 8;
    const STATUS_OUTLAW = 16;
    const STATUS_ADMIN = 32;

    const NORMAL = 1;
    const BANDIT = 2;
    const BANDIT_LORD = 3;
    const BANDIT_KING = 4;
    const STAR_LORD = 5;
    const EMPEROR = 6;
    const GRAND_EMPEROR = 7;

    static $filter_comparison = array(
        'i' => self::STATUS_INACTIVE,
        'I' => self::STATUS_30_INACTIVE,
        'v' => self::STATUS_VACATION,
        'b' => self::STATUS_BANNED,
        'o' => self::STATUS_OUTLAW,
        'a' => self::STATUS_ADMIN
    );

    public function getBy($universe_id, $player_id, $fields='*'){
        $universe_id =(int) $universe_id;
        $player_id =(int) $player_id;

        $row = DB::selectOne("SELECT $fields FROM players
            WHERE universe_id={$universe_id} AND player_id={$player_id} AND active=1");
        return $row;
    }

    public static function topPlayers($limit=10, $type=0, $country_id=0, $mode='normal'){

        $limit = (int) $limit;
        $type = (int) $type;
        if($limit < 1){ $limit = 1; }
        $suffix = $country_id? '-'.$country_id : '';
        return self::cacheQuery('top-players-'.$mode.'-'.$type.'-'.$limit.$suffix, function() use($limit, $type, $country_id, $mode){
            if($mode !== NULL){
                if($mode=='normal'){
                    $sql_mode = 'AND u.speed = 1 AND u.speed_fleet = 1 AND u.debris_factor = 0.3';
                } else {
                    $sql_mode = 'AND (u.speed > 1 OR u.speed_fleet > 1 OR u.debris_factor > 0.3)';
                }
            } else {
                $sql_mode = '';
            }
            if($country_id){
                $country_id = (int) $country_id;
                $sql_country = "AND u.country_id={$country_id}";
            } else{
                $sql_country = '';
            }

            $top = $limit+1; //performance optimization
            // Even if we are reading data from just one universe, we don't need the other records

            $sql = "SELECT rk.*, a.tag AS alliance_tag FROM (
              SELECT DISTINCT y.player_id, y.universe_id, y.name, y.alliance_id,
            	u.language, u.number, u.domain, m.local_name,
            	r.position AS ranking_position, r.score	AS ranking_score
                FROM players AS y
                INNER JOIN rankings AS r ON r.entity_id=y.player_id AND r.universe_id=y.universe_id
                  AND r.category=1 AND r.type=$type AND r.position < $top
                INNER JOIN universes AS u ON u.id=y.universe_id AND u.api_enabled=1 $sql_mode $sql_country
                INNER JOIN meta_universes AS m ON m.universe_id=u.id AND m.is_special=0
                WHERE y.active=1 ORDER BY score DESC LIMIT $limit) AS rk
              LEFT JOIN alliances AS a ON a.universe_id=rk.universe_id AND a.alliance_id=rk.alliance_id AND a.active=1";
            return DB::select($sql);
        }, 1440);
    }

    public static function countActiveInUniverse($universe_id){
        $universe_id =(int) $universe_id;

        $q = DB::selectOne("SELECT COUNT(*) AS c FROM players
            WHERE universe_id={$universe_id} AND active=1");
        return $q->c;
    }

    public static function countByStatus($universe_id, $status, $comparison='='){
        if(!in_array($comparison, array('=','|','&'))){ $comparison='='; }

        $universe_id =(int) $universe_id;
        $status = (int) $status;

        $q = DB::selectOne("SELECT COUNT(*) AS c FROM players
            WHERE universe_id={$universe_id} AND status $comparison $status AND active=1");
        return $q->c;
    }

    public function getFullInfo($universe_id, $player_id){
        $player_id = (int) $player_id;
        $universe_id = (int) $universe_id;

        $sql = "SELECT y.player_id, y.name, y.status, y.alliance_id, y.last_update, a.tag AS alliance_tag, r_0
        .position AS ranking_position, r_0.score AS ranking_score
            FROM players as y
            LEFT JOIN alliances AS a ON a.universe_id=y.universe_id AND a.alliance_id=y.alliance_id
            LEFT JOIN rankings AS r_0 ON r_0.universe_id={$universe_id} AND r_0.type=0 AND r_0.category=1 AND r_0.active=1 AND y.player_id=r_0.entity_id
            WHERE y.universe_id={$universe_id} AND y.player_id={$player_id}";

        $row = DB::selectOne($sql);
        if( isset($row->status) ){
            $row->string_status = self::numberToStatus($row->status);
            return $row;
        }
        return NULL;
    }

    function getFromAlliance($universe_id, $alliance_id){
        $alliance_id = (int) $alliance_id;
        $universe_id = (int) $universe_id;

        $sql = "SELECT pal.*, r_7.position AS honor_position, r_7.score AS honor_score FROM 
            (SELECT y.player_id, y.name, y.status, y.last_update, r_0.position AS ranking_position, r_0.score AS ranking_score, ym.views
            FROM players as y
            LEFT JOIN players_meta AS ym ON ym.universe_id={$universe_id} AND ym.player_id=y.player_id
            LEFT JOIN rankings AS r_0 ON r_0.type=0 AND r_0.universe_id={$universe_id} AND r_0.category=1 AND y.player_id=r_0.entity_id
            WHERE y.alliance_id=$alliance_id AND y.universe_id={$universe_id} AND y.active=1 ORDER BY ranking_position) AS pal
            LEFT JOIN rankings AS r_7 ON r_7.type=7 AND r_7.universe_id={$universe_id} AND r_7.category=1 AND pal.player_id=r_7.entity_id
            ORDER BY ranking_position";

        $rows = DB::select($sql);
        if($rows){
            foreach($rows as $row){
                $row->string_status = self::numberToStatus($row->status);
            }
            return $rows;
        }
        return array();
    }

    function searchCount($universe_id, $text='', $filters = NULL, $filters_comparison='&'){
        $universe_id = (int) $universe_id;
        $sql = "SELECT COUNT(*) AS c FROM players AS y
            WHERE y.universe_id={$universe_id} AND y.active=1";
        $params = [];

        if($text){
            $sql .= " AND y.name LIKE ? ESCAPE '='";
            $params[] = '%'.Security::escapeLike($text,'=').'%';
        }
        if($filters && $filters != '-'){
            $number = self::statusToNumber($filters);
            if($filters_comparison!='&'){
                $filters_comparison = '=';
            }
            $sql .= " AND y.status ".$filters_comparison." ".$number;
        }
        $row = DB::selectOne($sql, $params);
        return $row->c;
    }

    function search($universe_id, $text='', $limit=10, $offset=0, $order_by='ranking_position',$order='ASC', $filters = NULL, $filters_comparison='&'){
        $offset = (int) $offset;
        if($offset < 0){
            $offset = 0;
        }
        $limit = (int) $limit;
        if($limit < 1){ $limit = 1; }
        $universe_id = (int) $universe_id;

        $params = [];

        $sql = "SELECT y.player_id, y.name, y.status, y.alliance_id, y.last_update, a.tag AS alliance_tag, r_0.position AS ranking_position
            FROM players AS y
            LEFT JOIN alliances AS a ON y.universe_id=a.universe_id AND y.alliance_id=a.alliance_id
            LEFT JOIN rankings AS r_0 ON r_0.type=0 AND y.player_id=r_0.entity_id AND r_0.universe_id={$universe_id} AND r_0.category=1
            WHERE y.universe_id={$universe_id}";
        if($text){
            $sql .= " AND y.name LIKE ? ESCAPE '='";
            $params[] = '%'.Security::escapeLike($text,'=').'%';
        }
        if($filters && $filters != '-'){
            $number = self::statusToNumber($filters);
            if($filters_comparison!='&'){
                $filters_comparison = '=';
            }
            $sql .= " AND y.status ".$filters_comparison." ".$number;
        }
        $sql .= ' AND y.active=1 ORDER BY ISNULL(ranking_position) ASC, '.$order_by.' '.$order.' LIMIT '.$limit.' OFFSET '.$offset;

        $rows = DB::select($sql, $params);

        if($rows){
            foreach($rows as $row){
                $row->string_status = self::numberToStatus($row->status);
            }
            return $rows;
        }
        return array();
    }

    //Just dump all that info bro
    function allPlayersExport($universe_id, $timestamp){
        $universe_id = (int) $universe_id;
        $sql = "SELECT * FROM players WHERE universe_id=$universe_id AND active=1";
        if(!is_null($timestamp)){
            $timestamp = (int) $timestamp;
            $sql .= " AND last_update>$timestamp";
        }
        return DB::select($sql);
    }

    public function softDeleteInUniverse($universe_id){
        DB::statement("UPDATE players SET active=0 WHERE universe_id=".$universe_id);
    }

    public static function statusToNumber($rawStatus){
        $filter_comparison = self::$filter_comparison;
        $num = 0;
        $filter_array = str_split($rawStatus);
        foreach($filter_array as $filter){
            if(isset($filter_comparison[$filter])){
                $num |= $filter_comparison[$filter];
            }
        }
        return $num;
    }

    public static function numberToStatus($number){
        $filter_comparison = self::$filter_comparison;

        $filters = '';
        foreach($filter_comparison as $k => $filter){
            if($filter & $number){
                $filters .= $k;
            }
        }
        return $filters;
    }

}