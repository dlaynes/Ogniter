<?php
namespace App\Ogniter\Model\Ogame;

use App\Ogniter\Model\BaseModel;
use App\Ogniter\Tools\Sql\Query\Security;
use DB;

class Planet extends BaseModel
{

    protected $table = "planets";

    public $timestamps = false;

    const PLANET = 1;
    const MOON = 2;
    const DEBRIS_FIELD = 3;

    public static function countPlanets($universe_id, $type){
        $universe_id = (int) $universe_id;
        $type = (int) $type;
        return DB::selectOne("SELECT COUNT(*) AS c FROM `planets` WHERE universe_id =$universe_id AND active=1 AND type=$type")->c;
    }

    function countWorldPlanets( $type=1){
        $type = (int) $type;
        return DB::selectOne("SELECT COUNT(*) AS c FROM `planets` WHERE active=1 AND type=$type")->c;
    }

    function countDomainPlanets($country_id, $type=1){
        $country_id = (int) $country_id;
        $type = (int) $type;

        return DB::selectOne("SELECT COUNT(*) AS c FROM `planets`
			LEFT JOIN universes AS s ON s.id=planets.universe_id
			WHERE s.country_id =$country_id AND planets.active=1 AND planets.type=$type")->c;
    }


    public function getPlanets($universe_id, $galaxy, $system){
        $universe_id = (int) $universe_id;
        $galaxy = (int) $galaxy;
        $system = (int) $system;

        //FIX ME: para las lunas no se deberian jalar los datos de alianzas y ranking... Agregar la busqueda de escombros en el futuro
        $sql = "SELECT ps.*, r_7.position AS honor_position, r_7.score AS honor_score, a.tag AS alliance_tag
            FROM (SELECT p.universe_id, p.planet_id, p.player_id, p.name, p.position, p.type, p.size,
            y.name AS player_name, y.status AS player_status, y.alliance_id, r_0.position AS ranking_position,
			(SELECT COUNT(*) FROM planet_changes AS pc
				WHERE pc.universe_id={$universe_id} AND pc.planet_id=p.planet_id) AS change_count
            FROM planets AS p
            INNER JOIN players AS y ON y.universe_id={$universe_id} AND p.player_id = y.player_id
            INNER JOIN rankings AS r_0 ON r_0.universe_id={$universe_id} AND y.player_id=r_0.entity_id AND r_0.type=0 AND r_0.category=1
            WHERE p.universe_id={$universe_id} AND p.galaxy={$galaxy} AND p.system={$system} AND p.active=1) AS ps
            LEFT JOIN rankings AS r_7 ON r_7.universe_id={$universe_id} AND ps.player_id=r_7.entity_id AND r_7.type=7 AND r_7.category=1
            LEFT JOIN alliances AS a ON a.universe_id=ps.universe_id AND ps.alliance_id = a.alliance_id";

        $rows = DB::select($sql);

        $data = array(
            'planets' => array(),
            'moons' => array()
        );

        if(count($rows)){
            foreach($rows as $pl){
                $pl->player_status_string = Player::numberToStatus($pl->player_status);
                if($pl->type == self::PLANET){
                    $data['planets'][$pl->position] = $pl;
                } else{
                    $data['moons'][$pl->position] = $pl;
                }
            }
        }
        return $data;
    }

    function search($universe_id, $text='', $limit=10, $offset=0, $order_by='ranking_position',$order='ASC'){
        $offset = (int) $offset;
        if($offset < 0){
            $offset = 0;
        }
        $limit = (int) $limit;
        if($limit < 1){ $limit = 1; }
        $universe_id = (int) $universe_id;

        $sql = "SELECT p.planet_id, p.player_id, p.name, p.galaxy, p.system, p.position, p.type, p.size, p.last_update,
        	y.name AS player_name, y.status AS player_status, r_0.position AS ranking_position
        	FROM planets AS p
        	INNER JOIN players AS y ON p.player_id=y.player_id AND y.universe_id={$universe_id} AND y.active=1
        	LEFT JOIN rankings AS r_0 ON r_0.type=0 AND y.player_id=r_0.entity_id AND r_0.universe_id={$universe_id} AND r_0.category=1
        	WHERE p.universe_id={$universe_id} AND p.active=1";

        $params = [];
        if($text){
            $sql .= " AND p.name LIKE ? ESCAPE '='";
            $params[] = '%'.Security::escapeLike($text,'=').'%';
        }
        $sql .= ' AND p.active=1 ORDER BY '.$order_by.' '.$order.' LIMIT '.$limit.' OFFSET '.$offset;
        $rows = DB::select($sql, $params);
        if($rows){
            foreach($rows as $row){
                $row->string_status = Player::numberToStatus($row->player_status);
            }
            return $rows;
        }
        return array();
    }

    function searchCount($universe_id, $text=''){
        $universe_id = (int) $universe_id;
        $sql = "SELECT COUNT(*) AS c FROM planets AS p
        	INNER JOIN players AS y ON y.universe_id={$universe_id} AND p.player_id=y.player_id AND y.active=1
        	 WHERE p.universe_id={$universe_id}";

        $params = [];
        if($text){
            $sql .= " AND p.name LIKE ? ESCAPE '='";
            $params[] = '%'.Security::escapeLike($text,'=').'%';
        }
        $sql .= ' AND p.active=1';

        $row = DB::selectOne($sql, $params);
        return $row->c;
    }


    function getFromPlayer($universe_id,  $player_id){
        $universe_id = (int) $universe_id;
        $og_player_id = (int) $player_id;

        $sql = "SELECT p.planet_id, p.player_id, p.name, p.galaxy, p.system, p.position, p.type, p.size, p.last_update,
			(SELECT COUNT(*) FROM planet_changes AS pc
				WHERE pc.universe_id={$universe_id} AND pc.planet_id=p.planet_id) AS conteo_cambios
			FROM planets AS p
			WHERE p.universe_id={$universe_id} AND p.player_id={$player_id} AND p.active=1";
        return DB::select($sql);
    }

    function getPlanetDetail($universe_id, $planet_id){
        $universe_id = (int) $universe_id;
        $planet_id = (int) $planet_id;

        $sql = "SELECT p.planet_id, p.name, p.galaxy, p.system, p.position, p.type, p.size
		FROM planets AS p
    	WHERE p.planet_id=$planet_id AND p.universe_id=$universe_id";

        return DB::selectOne($sql);
    }

    function getActivePlanets($universe_id, $galaxy, $alliance_id=NULL, $position=NULL, $status=NULL, $type=self::PLANET, $honor_type=NULL, $object_ids=NULL, $category=1 ){
        $universe_id = (int) $universe_id;
        $galaxy = (int) $galaxy;

        $pos = '';
        if($position){
            $position = (int) $position;
            $pos = 'AND p.position='.$position;
        } else{

        }
        $sql = "SELECT system, COUNT(*) AS count FROM planets AS p";
        $where = " WHERE p.universe_id=$universe_id AND p.galaxy=$galaxy $pos AND p.type={$type} AND p.active=1";
        $group = " GROUP BY system";

        if(!is_null($object_ids)){
            if($category==1){
                $sql = "SELECT system, p.player_id AS entity_id FROM planets as p
					INNER JOIN players AS y ON y.universe_id={$universe_id} AND y.active=1 AND p.player_id IN (".implode(',', $object_ids).")";
                $group = " GROUP BY system, entity_id";
            } else {
                $sql = "SELECT system, y.alliance_id AS entity_id FROM planets AS p
					INNER JOIN players AS y ON p.player_id=y.player_id AND y.universe_id={$universe_id} AND y.active=1 AND y.alliance_id IN (".implode(',', $object_ids).")";
                $group = " GROUP BY system, entity_id";
            }
        } else if(!is_null($alliance_id)){
            $alliance_id = (int) $alliance_id;
            $sql .= " INNER JOIN players AS y ON p.player_id=y.player_id AND y.universe_id={$universe_id} AND y.alliance_id=$alliance_id AND y.active=1";
        } else if(!is_null($status)){
            $status= (int) $status;
            $sql .= " INNER JOIN players AS y ON p.player_id=y.player_id AND y.universe_id={$universe_id} AND y.status=$status AND y.active=1";
        } else if(!is_null($honor_type)){
            switch($honor_type){
                case Player::BANDIT:
                    $player = new Player();
                    $count = $player->searchCount($universe_id);
                    if($count > 251){
                        $low_250 = $count - 251;
                    } else{
                        $low_250 = 0;
                    }
                    $extra =" AND r_7.position > $low_250 AND r_7.score < -499";
                    break;
                case Player::STAR_LORD:
                default:
                    $extra =" AND r_7.position < 249 AND r_7.score > 499";
                    break;
            }

            $sql .= " LEFT JOIN rankings AS r_7 ON r_7.type=7 AND r_7.entity_id=p.player_id AND r_7.universe_id={$universe_id} AND r_7.category=1 AND r_7.active=1 ".$extra."
				INNER JOIN players AS y ON r_7.entity_id=y.player_id AND y.universe_id={$universe_id} AND NOT (y.status & 35) AND y.active=1";
        }
        $sql .= $where.$group;
        return DB::select($sql);
    }
    
    //Yeah, right
    function allPlanetsExport($universe_id, $timestamp){
        $universe_id = (int) $universe_id;
        $sql = "SELECT * FROM planets WHERE universe_id=$universe_id AND active=1";
        if(!is_null($timestamp)){
            $timestamp = (int) $timestamp;
            $sql .= " AND last_update>$timestamp";
        }
        return DB::select($sql);
    }

    function softDeleteInUniverse($universe_id){
        $universe_id = (int) $universe_id;
        \DB::statement("UPDATE planets SET active=0 WHERE universe_id=".$universe_id);
    }
    
}