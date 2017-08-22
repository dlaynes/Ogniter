<?php

namespace App\Ogniter\Model\Ogame;

use App\Ogniter\Tools\Sql\Query\Security;
use DB;
use App\Ogniter\Model\BaseModel;

class Alliance extends BaseModel {

    protected $table = "alliances";

    public $timestamps = false;


    public static function countActiveInUniverse($universe_id){
        $universe_id =(int) $universe_id;

        $q = DB::selectOne("SELECT COUNT(*) AS c FROM alliances
            WHERE universe_id={$universe_id} AND active=1");
        return $q->c;
    }

    //Be nice
    public function getBy($universe_id, $alliance_id, $fields='*'){
        $universe_id =(int) $universe_id;
        $alliance_id =(int) $alliance_id;

        $row = DB::selectOne("SELECT $fields FROM alliances
            WHERE universe_id={$universe_id} AND alliance_id={$alliance_id} AND active=1");
        return $row;
    }

    public static function topAlliancesCacheId($limit=10, $type=0, $country_id=0, $mode='normal'){
        $limit = (int) $limit;
        $type = (int) $type;
        if($limit < 1){ $limit = 1; }
        $suffix = $country_id? '-'.$country_id : '';
        return 'top-alliances-'.$mode.'-'.$type.'-'.$limit.$suffix;
    }

    public static function topAlliances($limit=10, $type=0, $country_id=0, $mode='normal'){
        $limit = (int) $limit;
        $type = (int) $type;
        if($limit < 1){ $limit = 1; }
        return self::cacheQuery(self::topAlliancesCacheId($limit,$type,$country_id,$mode),
            function() use($limit, $type, $country_id, $mode){
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

            $top = $limit+1; //performance optimization.
            // Even if we are reading data from just one universe, we don't need the other records

            $sql = "SELECT DISTINCT a.alliance_id, a.universe_id, a.name, a.tag,
            	u.language, u.number, u.domain, m.local_name,
            	r.position AS ranking_position, r.score	AS ranking_score
                FROM alliances AS a
                INNER JOIN rankings AS r ON r.entity_id=a.alliance_id AND r.universe_id=a.universe_id
                  AND r.category=2 AND r.type=$type AND r.position < $top
                INNER JOIN universes AS u ON u.id=a.universe_id AND u.api_enabled=1 $sql_mode $sql_country
                INNER JOIN meta_universes AS m ON m.universe_id=u.id AND m.is_special=0
                WHERE a.active=1 ORDER BY score DESC LIMIT $limit";
            return DB::select($sql);
        }, 1440);
    }


    function search($universe_id, $field, $text='', $limit=10, $offset=0, $order_by='name',$order='ASC'){
        $universe_id = (int) $universe_id;
        $offset = (int) $offset;
        if($offset < 0){
            $offset = 0;
        }
        $limit = (int) $limit;
        if($limit < 1){ $limit = 1; }

        $sql = "SELECT a.alliance_id, a.name, a.tag, a.last_update,
            (SELECT COUNT(*) FROM players AS y WHERE a.alliance_id=y.alliance_id AND y.universe_id={$universe_id} AND y.active=1) AS ally_members, r_0.position AS ranking_position
            FROM alliances AS a
            LEFT JOIN rankings AS r_0
            ON r_0.type=0 AND a.alliance_id=r_0.entity_id AND r_0.universe_id={$universe_id} AND r_0.category=2
            WHERE a.universe_id={$universe_id} AND a.active=1";

        if($field!='tag'){
            $field = 'name';
        }
        
        $params = [];
        
        if($text){
            $sql .= " AND a.$field LIKE ? ESCAPE '='";
            $params[] = '%'.Security::escapeLike($text,'=').'%';
        }
        $sql .= ' ORDER BY ISNULL(ranking_position) ASC,'.$order_by.' '.$order.' LIMIT '.$limit.' OFFSET '.$offset;

        return DB::select($sql, $params);
    }

    function searchCount($universe_id, $field, $text=''){
        $sql = "SELECT COUNT(*) AS c
            FROM alliances AS a
            WHERE a.universe_id={$universe_id} AND a.active=1 ";

        $params = [];

        if($text){
            $sql .= " AND a.$field LIKE ? ESCAPE '='";
            $params[] = '%'.Security::escapeLike($text,'=').'%';
        }
        $row = DB::selectOne($sql, $params);
        return $row->c;
    }

    public function getFullInfo($universe_id, $alliance_id){
        $universe_id = (int) $universe_id;
        $alliance_id = (int) $alliance_id;
        $sql = "SELECT a.alliance_id, a.name, a.tag, a.homepage, a.logo, a.open, a.last_update, r_0.position AS ranking_position,
          r_0.score AS ranking_score
          FROM  alliances AS a
          LEFT JOIN rankings AS r_0
          ON r_0.type=0 AND r_0.universe_id={$universe_id} AND r_0.category=2 AND r_0.active=1 AND a.alliance_id=r_0.entity_id
          WHERE a.universe_id={$universe_id} AND a.alliance_id={$alliance_id}";
        return DB::selectOne($sql);
    }

    function allAlliancesExport($universe_id, $timestamp){
        $universe_id = (int) $universe_id;
        $sql = "SELECT * FROM alliances WHERE universe_id=$universe_id AND active=1";
        if(!is_null($timestamp)){
            $timestamp = (int) $timestamp;
            $sql .= " AND last_update>$timestamp";
        }
        return DB::select($sql);
    }

    public function softDeleteInUniverse($universe_id){
        DB::statement("UPDATE alliances SET active=0 WHERE universe_id=".$universe_id);
    }

}