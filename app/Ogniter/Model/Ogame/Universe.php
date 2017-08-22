<?php
namespace App\Ogniter\Model\Ogame;

use App\Ogniter\Model\BaseModel;
use App\Ogniter\Model\DummyObject;
use DB;

class Universe extends BaseModel {

    protected $table = "universes";

    protected static $currentUniverse = NULL;

    /*
    protected $fillable = [
        'country_id', 'ogame_code', 'name',
        'language', 'timezone', 'domain',
        'version', 'speed', 'speed_fleet',
        'galaxies', 'systems', 'extra_fields',
        'acs', 'rapidfire', 'def_to_debris',
        'debris_factor', 'repair_factor', 'newbie_protection_limit',
        'newbie_protection_high', 'top_score', 'last_update',
        'donut_galaxy', 'donut_system', 'active', 'created_at'
    ];

    public function metaUniverse()
    {
        return $this->hasOne('App\MetaUniverse');
    }
    */

    public static function newIfNotAvailable($language, $ogame_server_id){
        $query = static::where('language', '=', $language)
            ->where('ogame_code','=', $ogame_server_id);
        $row = $query->first();
        if(!$row) {
            $row = new static();
            $row->language = $language;
            $row->ogame_code = $ogame_server_id;
        }
        return $row;
    }

    public static function getUniversesFromCacheId($lang){
        return 'universe-list.'.$lang;
    }

    public static function getUniversesFrom($lang){
        return self::cacheQuery(self::getUniversesFromCacheId($lang), function() use ( $lang ){
            $sql = "SELECT u.id, m.local_name, u.speed, u.speed_fleet, u.acs, u.rapidfire, u.api_enabled,
            u.api_v6_enabled, u.wf_enabled, u.debris_factor_def,
            u.def_to_debris, u.debris_factor, u.galaxies, u.systems, u.donut_galaxy, u.donut_system, u.global_deuterium_save_factor,
            (SELECT COUNT(*) FROM players AS y WHERE u.id=y.universe_id AND y.active=1) AS num_players,
            (SELECT MAX(score) FROM rankings AS r WHERE r.universe_id=u.id
              AND r.category=1 AND r.type=0 AND r.position=1 AND r.active=1) AS high_score
            FROM universes AS u
            JOIN meta_universes AS m ON m.universe_id=u.id
            JOIN countries AS c ON c.id=u.country_id
            WHERE c.language=? AND u.active=1 ORDER BY u.api_enabled DESC, m.weight, u.id";

            $list = DB::select($sql,[$lang]);

            return $list;
        }, 1440 );
    }

    static function setCurrentUniverse($universe){
        self::$currentUniverse = $universe;
    }

    static function getCurrentUniverse(){
        if(self::$currentUniverse==NULL){
            return new DummyObject();
        }
        return self::$currentUniverse;
    }

    static function createHighscoreTableSQL($language, $universe_id){
        $table_name = self::getHighscoreTableName($language,$universe_id);
        return "CREATE TABLE IF NOT EXISTS {$table_name} LIKE ranking_history_template";
    }

    static function getHighscoreTableName($language, $universe_id){
        return "z_{$language}_{$universe_id}_ranking_history";
    }

    public static function getUniverseByIdCacheId($universe_id){
        return 'universe-'.$universe_id;
    }

    public function getUniverseById($universe_id){
        $universe_id = (int) $universe_id;
        //TODO: cache id
        return self::cacheQuery(self::getUniverseByIdCacheId($universe_id), function() use ($universe_id){
            $sql = "SELECT m.local_name, u.id, u.country_id, u.ogame_code, u.language, u.domain, u.timezone, u.version, u.speed,
                u.speed_fleet, u.galaxies, u.systems, u.acs, u.rapidfire, u.def_to_debris, u.debris_factor, u.debris_factor_def,
                u.repair_factor, u.newbie_protection_limit, u.newbie_protection_high, u.donut_galaxy, u.donut_system,
                u.last_update, m.weight, u.extra_fields, u.active, u.api_enabled, u.api_v6_enabled, u.wf_enabled,
                u.wf_minimun_res_lost, u.wf_minimun_loss_perc, u.wf_basic_percentage_repair, u.global_deuterium_save_factor,
                (SELECT MAX(score) FROM rankings AS r WHERE r.universe_id=$universe_id AND r.category=1 AND r.type=1
                  AND r.position=1 AND r.active=1) AS highscore
                FROM universes AS u
                JOIN meta_universes AS m ON m.universe_id=u.id
                WHERE u.id=".$universe_id;

            $list = DB::select($sql);
            return isset($list[0]) ? $list[0] : NULL;

        }, 1440);
    }

    public static function extractParts($url){
        //https://s115-br.ogame.gameforge.com/api/universes.xml
        $server_domain = parse_url($url, PHP_URL_HOST);
        $fragments = explode('.', $server_domain);
        $base = explode("-", $fragments[0]);
        $api_base = substr($server_domain,strpos($server_domain, '-')+1);
        return [
            'api_base' => $api_base,
            'domain' => $server_domain,
            'language' => isset($base[1]) ? $base[1] : NULL,
            'number' => str_replace('s', '', $base[0])
        ];
    }

    static function formatDomain($language, $ogame_server_id){
        return 's'.$ogame_server_id.'-'.$language.'.ogame.gameforge.com';
    }
}
