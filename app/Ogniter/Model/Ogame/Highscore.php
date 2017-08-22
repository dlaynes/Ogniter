<?php
namespace App\Ogniter\Model\Ogame;

use App\Ogniter\Model\BaseModel;
use DB;

class Highscore extends BaseModel {

    /* Deprecate this function!
    static function countFromUpdate($language, $universe_id, $category, $type, $last_update){
        $universe_id = (int) $universe_id;
        $category = (int) $category;
        $type = (int) $type;
        $last_update = (int) $last_update;

        $table_name = Universe::getHighscoreTableName($language, $universe_id);

        return DB::selectOne("SELECT COUNT(*) AS c FROM {$table_name} WHERE
          category=$category AND type=$type AND last_update=$last_update")->c;
    } */

    //Deprecate this function too
    static function countAllFromUpdate($language, $universe_id, $last_update){
        $universe_id = (int) $universe_id;
        $last_update = (int) $last_update;

        $table_name = Universe::getHighscoreTableName($language, $universe_id);

        return DB::selectOne("SELECT COUNT(*) AS c FROM {$table_name} WHERE last_update=$last_update")->c;
    }

    static function resetRankingsByGroup($universe_id,$category,$type){
        $universe_id = (int) $universe_id;
        $category = (int) $category;
        $type = (int) $type;
        DB::statement("UPDATE rankings SET active=0 WHERE universe_id={$universe_id}
          AND category={$category} AND type={$type}");
    }

    //Deprecate?
    static function resetRankings($universe_id){
        $universe_id = (int) $universe_id;
        DB::statement("UPDATE rankings SET active=0 WHERE universe_id={$universe_id}");
    }

    static function replaceRankings($tbl_name, $universe_id, $time, $reset=TRUE){
        $universe_id = (int) $universe_id;
        if($reset){
            self::resetRankings($universe_id);
        }
        $sql = "REPLACE INTO rankings (`universe_id`,`entity_id`,`type`,`category`,`last_update`,
              `position`,`score`,`ships`,`active`)
              SELECT {$universe_id}, z.`entity_id`,z.`type`,z.`category`,z.`last_update`,
              z.`position`,z.`score`,z.`ships`, 1 FROM {$tbl_name} AS z
            WHERE z.last_update=".$time;
        DB::unprepared($sql);
    }

    /* Deprecate this function!
    static function replaceRankingHistory($tbl_name, $universe_id, $time){
        $universe_id = (int) $universe_id;
        $sql = "INSERT IGNORE INTO ranking_history (`universe_id`,`entity_id`,`type`,`category`,`last_update`,
              `position`,`score`,`ships`)
              SELECT {$universe_id}, z.`entity_id`,z.`type`,z.`category`,z.`last_update`,
              z.`position`,z.`score`,z.`ships` FROM {$tbl_name} AS z
            WHERE z.last_update=".$time;
        DB::unprepared($sql);
    } */

    //TODO: pasar previous month y previous week como parámetros
    function getResultsFrom($language, $universe_id, $object_id, $category, $type, $reference_timestamp=NULL){
        if(is_null($reference_timestamp)){
            $reference_timestamp = time();
        }

        $table_name = Universe::getHighscoreTableName($language, $universe_id);

        $beginning_week = $reference_timestamp - 7*3600*24;
        $beginning_month = $reference_timestamp - 30*3600*24;

        //$latest_update = (int) HighscoreLog::getLatestRankingUpdate($universe_id,$category,$type);
        $previous_month_ts = (int) HighscoreLog::getRankingUpdateNear($universe_id, $beginning_month, $category, $type);
        $previous_week_ts = (int) HighscoreLog::getRankingUpdateNear($universe_id, $beginning_week, $category, $type);

        $universe_id = (int) $universe_id;

        //FIX ME: pedir la info de naves en la consulta personal
        $type = (int) $type;
        $sql = "SELECT r_$type.position, r_$type.score, r_$type.ships,
			(
				SELECT CONCAT(wd.score,',', wd.position) FROM {$table_name} AS wd
				WHERE wd.entity_id=$object_id AND wd.type={$type} AND wd.category=$category
				AND wd.last_update = $previous_week_ts
				 LIMIT 1
			) AS weekly_score,
			(
				SELECT CONCAT(md.score,',', md.position) FROM {$table_name} AS md
				WHERE md.entity_id=$object_id AND md.type={$type} AND md.category=$category
				AND md.last_update = $previous_month_ts
				LIMIT 1
			) AS monthly_score
		FROM rankings AS r_$type
		WHERE r_$type.type=".$type." AND r_$type.entity_id=$object_id
		    AND r_$type.universe_id={$universe_id} AND r_$type.category=$category AND r_$type.active=1 LIMIT 1";
        return DB::selectOne($sql);
    }

    function countList($universe_id, $category, $type){
        $universe_id = (int) $universe_id;
        $type = (int) $type;
        $category = (int) $category;
        if($category==1){
            $sql = "SELECT COUNT(*) AS c FROM rankings AS r_$type
				INNER JOIN players AS y ON r_$type.type=".$type." AND y.universe_id=r_$type.universe_id AND r_$type.entity_id=y.player_id
				WHERE r_$type.universe_id=$universe_id AND r_$type.category=1 AND y.active=1";
        } else {
            $sql = "SELECT COUNT(*) AS c FROM rankings AS r_$type
				INNER JOIN alliances AS a ON r_$type.type=".$type." AND a.universe_id=r_$type.universe_id AND r_$type.entity_id=a.alliance_id
				WHERE r_$type.universe_id=$universe_id AND r_$type.category=2 AND a.active=1";
        }
        return DB::selectOne($sql)->c;
    }

    function getList($language, $universe_id, $category, $type, $limit=100, $offset=0, $order_by='position',$order='DESC',
                     $reference_timestamp=NULL){

        if(is_null($reference_timestamp)){ $reference_timestamp = time(); }

        $beginning_week = $reference_timestamp - 7*3600*24;
        $beginning_month = $reference_timestamp - 30*3600*24;

        //$latest_update = (int) HighscoreLog::getLatestRankingUpdate($universe_id,$category,$type);
        $previous_month_ts = (int) HighscoreLog::getRankingUpdateNear($universe_id, $beginning_month, $category, $type);
        $previous_week_ts = (int) HighscoreLog::getRankingUpdateNear($universe_id, $beginning_week, $category, $type);

        $offset = (int) $offset;
        if($offset < 0){
            $offset = 0;
        }
        $limit = (int) $limit;
        if($limit < 1){ $limit = 1; }
        $type = (int) $type;

        $ships = ($type==3)? ', r_'.$type.'.ships':'';

        $universe_id = (int) $universe_id;

        $table_name = Universe::getHighscoreTableName($language, $universe_id);

        if($category==1){

            if($type!=7){
                $latest_update_honor = (int) HighscoreLog::getLatestRankingUpdate($universe_id,1,7);
                $extra = ", (SELECT CONCAT(r_7.position, ',', r_7.score )
					FROM rankings AS r_7 WHERE r_7.type=7 AND r_7.entity_id=r.entity_id AND r_7.universe_id = $universe_id AND r_7.category=1
					AND r_7.last_update={$latest_update_honor} LIMIT 1
				) AS honor_info";
            } else {
                $extra = '';
            }

            $sql = "SELECT r.*, a.tag AS alliance_tag $extra,
                    wd.position AS weekly_position, md.position AS monthly_position,
                    r.score - IFNULL(wd.score,0) AS weekly_difference,
                    r.score - IFNULL(md.score,0) AS monthly_difference FROM 
                ( SELECT r_$type.entity_id, r_$type.position, r_$type.score $ships,
                    y.name AS player_name, y.status AS player_status, y.alliance_id
                FROM rankings AS r_$type					
				INNER JOIN players AS y USE INDEX(primary) ON r_$type.entity_id=y.player_id AND  y.universe_id=r_$type.universe_id
                    AND !(y.status&".Player::STATUS_BANNED.") AND y.active=1					
                    WHERE r_$type.universe_id={$universe_id} AND r_$type.category=1 AND r_$type.type={$type}
                    AND r_$type.position BETWEEN ".($offset+1)." AND ".($offset+$limit).") AS r
				LEFT JOIN (
					SELECT entity_id,score, position FROM {$table_name} USE INDEX(hs)
					WHERE type={$type} AND category=1 AND last_update = $previous_week_ts
				) AS wd ON wd.entity_id=r.entity_id
				LEFT JOIN (
					SELECT entity_id,score,position FROM {$table_name} USE INDEX(hs)
					WHERE type={$type} AND category=1 AND last_update = $previous_month_ts
				) AS md ON md.entity_id=r.entity_id
				LEFT JOIN alliances AS a ON a.universe_id={$universe_id} AND r.alliance_id=a.alliance_id ORDER BY position";

            /*
            $sql = "SELECT r_sorted.*, a.tag AS alliance_tag $extra FROM 
                ( SELECT DISTINCT * FROM ( SELECT r_$type.entity_id, r_$type.position, r_$type.score $ships,
                  y.name AS player_name, y.status AS player_status, y.alliance_id,
                  wd.position AS weekly_position, md.position AS monthly_position,
                  r_$type.score - IFNULL(wd.score,0) AS weekly_difference,
                  r_$type.score - IFNULL(md.score,0) AS monthly_difference
                  FROM rankings AS r_$type USE INDEX(PRIMARY)
				LEFT JOIN (
					SELECT entity_id,score, position FROM {$ranking_table} USE INDEX(hs)
					WHERE type={$type} AND category=1 AND last_update = $previous_week_ts
				) AS wd ON wd.entity_id=r_$type.entity_id					
				LEFT JOIN (
					SELECT entity_id,score,position FROM {$ranking_table} USE INDEX(hs)
					WHERE type={$type} AND category=1 AND last_update = $previous_month_ts
				) AS md ON md.entity_id=r_$type.entity_id
					INNER JOIN players AS y ON r_$type.type={$type} AND y.universe_id=r_$type.universe_id
					AND r_$type.entity_id=y.player_id AND !(y.status&".Player::STATUS_BANNED.") AND y.active=1					
					WHERE r_$type.universe_id={$universe_id} AND r_$type.category=1 ) AS r
					ORDER BY $order_by $order LIMIT $limit OFFSET $offset
					) AS r_sorted
					LEFT JOIN alliances AS a ON a.universe_id={$universe_id} AND r_sorted.alliance_id=a.alliance_id";
            */
        } else {
            $sql = "SELECT r.*, (SELECT COUNT(*) FROM players AS y
                WHERE y.universe_id={$universe_id} AND y.alliance_id=r.entity_id AND y.active=1) AS ally_members,
                wd.position AS weekly_position, md.position AS monthly_position,
                r.score - IFNULL(wd.score,0) AS weekly_difference,
                r.score - IFNULL(md.score,0) AS monthly_difference
				FROM (
				    SELECT r_$type.position, r_$type.score, r_$type.entity_id, a.name AS alliance_name, a.tag AS alliance_tag
                FROM rankings AS r_$type USE INDEX(primary)
				INNER JOIN alliances AS a USE INDEX(primary)
				ON r_$type.entity_id=a.alliance_id AND a.universe_id=r_$type.universe_id AND a.active=1 
				WHERE r_$type.universe_id={$universe_id} AND r_$type.category=2 AND r_$type.type={$type}
				AND r_$type.position BETWEEN ".($offset+1)." AND ".($offset+$limit).")
				AS r
	  			LEFT JOIN (
					SELECT entity_id,score, position FROM {$table_name} USE INDEX(hs)
					WHERE type={$type} AND category=2 AND last_update = $previous_week_ts
				) AS wd ON wd.entity_id=r.entity_id
				LEFT JOIN (
					SELECT entity_id,score,position FROM {$table_name} USE INDEX(hs)
					WHERE type={$type} AND category=2 AND last_update = $previous_month_ts
				) AS md ON md.entity_id=r.entity_id
				ORDER BY position";

            /*
            $sql = "SELECT r_sorted.*, (SELECT COUNT(*) FROM players AS y WHERE y.universe_id={$universe_id} AND y.alliance_id=r_sorted.entity_id AND y.active=1) AS ally_members
				FROM (
				  SELECT DISTINCT * FROM (
				    SELECT r_$type.position, r_$type.score, r_$type.entity_id, a.name AS alliance_name, a.tag AS alliance_tag,
				    wd.position AS weekly_position, md.position AS monthly_position,
				    r_$type.score - IFNULL(wd.score,0) AS weekly_difference,
				    r_$type.score - IFNULL(md.score,0) AS monthly_difference
                    FROM rankings AS r_$type USE INDEX (PRIMARY)
				LEFT JOIN (
					SELECT entity_id,score, position FROM {$ranking_table} USE INDEX(hs)
					WHERE type={$type} AND category=2 AND last_update = $previous_week_ts
				) AS wd ON wd.entity_id=r_$type.entity_id
				LEFT JOIN (
					SELECT entity_id,score,position FROM {$ranking_table} USE INDEX(hs)
					WHERE type={$type} AND category=2 AND last_update = $previous_month_ts
				) AS md ON md.entity_id=r_$type.entity_id
				    INNER JOIN alliances AS a ON r_$type.type={$type} AND a.universe_id=r_$type.universe_id AND r_$type.entity_id=a.alliance_id AND a.active=1 
					WHERE r_$type.universe_id={$universe_id} AND r_$type.category=2
				  ) AS r
				  ORDER BY $order_by $order LIMIT $limit OFFSET $offset
				) AS r_sorted";
            */
        }

        $rows = DB::select($sql);
        if($rows){
            if($category==1){
                foreach($rows as $row){
                    $row->string_status = Player::numberToStatus($row->player_status);
                }
            }
            return $rows;
        }
        return array();
    }


    static function getTopFlopCacheId($universe_id, $category, $type, $order='ASC', $range='by_day'){
        return 'top-flop-'.$universe_id.'-'.$category.'-'.$type.'-'.$order.'-'.$range;
    }

    function getTopFlop($language, $universe_id, $category, $type, $last_update, $previous_update, $order='ASC', $limit=20, $range='by_day'){
        $universe_id = (int) $universe_id;
        $type = (int) $type;
        $last_update = (int) $last_update;
        $category = (int) $category;
        if($order!='ASC'){
            $order = 'DESC';
        }

        return self::cacheQuery(self::getTopFlopCacheId($universe_id, $category, $type, $order, $range),
            function() use($language, $universe_id, $category, $type,$last_update,$previous_update,$order,$limit){

                $last_update = (int) $last_update;
                $previous_update = (int) $previous_update;

                $table_name = Universe::getHighscoreTableName($language, $universe_id);

                $not_null = $previous_update != 0;
                $not_null_sql = $not_null ? 'WHERE difference IS NOT NULL':'';

                if($category==1){
                    if($type!=3){
                        $compare_field = 'score';
                    } else {
                        $compare_field = 'ships';
                    }

                    $sql = "SELECT dt.* FROM (
                        SELECT z.position, z.$compare_field, y.player_id, y.name AS player_name,
                          y.status AS player_status,
                        (SELECT CAST(z.$compare_field AS SIGNED)- CAST($compare_field AS SIGNED)
                        FROM {$table_name} USE INDEX(idx) WHERE entity_id=z.entity_id
                        AND category=1 AND type=$type AND last_update={$previous_update}
                        LIMIT 1) AS difference
                        FROM rankings AS z USE INDEX(primary)
                        INNER JOIN players AS y USE INDEX(primary) ON y.universe_id={$universe_id} AND z.entity_id=y.player_id AND y.active=1
                        WHERE z.universe_id={$universe_id} AND z.category=1 AND z.type={$type}
                    ) AS dt {$not_null_sql} ORDER BY difference $order LIMIT $limit";

                } else {
                    $sql = "SELECT dt.* FROM (
                        SELECT z.position, z.score, a.alliance_id, a.name AS alliance_name, a.tag AS alliance_tag,
                        (SELECT CAST(z.score AS SIGNED) - CAST(score AS SIGNED)
                        FROM {$table_name} USE INDEX(idx) WHERE entity_id=z.entity_id
                        AND category=2 AND type=$type AND last_update={$previous_update}
                        LIMIT 1) AS difference FROM rankings AS z USE INDEX(primary)
                        INNER JOIN alliances AS a USE INDEX(primary) ON a.universe_id={$universe_id} AND z.entity_id=a.alliance_id AND a.active=1
                        WHERE z.universe_id={$universe_id} AND z.category=2 AND z.type={$type}
                    ) AS dt {$not_null_sql} ORDER BY difference $order LIMIT $limit";
                }

                return DB::select($sql);
            }, 86400, TRUE
        );
    }


    function getDataFrom($language, $universe_id, $category, $type, $object_id, $beginning, $end=NULL){

        $type = (int) $type;
        $object_id = (int) $object_id;

        if($category==1){
            $sql = "SELECT position, score, last_update, ships FROM z_{$language}_{$universe_id}_ranking_history
					WHERE entity_id={$object_id} AND type={$type} AND category=1";
        } else{
            $sql = "SELECT position, score, last_update, ships FROM z_{$language}_{$universe_id}_ranking_history
					WHERE entity_id={$object_id} AND type={$type} AND category=2";
        }

        if(!is_null($beginning)){
            $beginning = (int) $beginning;
            $sql .= " AND last_update >= $beginning";
            if(!is_null($end)){
                $end = (int) $end;
                $sql .= " AND last_update <= $end";
            }
        }
        return DB::select($sql);
    }

}