<?php
namespace App\Ogniter\Model\Ogame;

use App\Ogniter\Model\BaseModel;
use App\Ogniter\Model\DummyObject;
use DB;

class Country extends BaseModel {

    protected $table = "countries";

    protected $fillable = ['language', 'old_domain', 'domain', 'flag', 'available', 'slug', 'api_domain' ];

    public $timestamps = false;

    protected static $currentCountry = NULL;

    static function getList(){
        return self::cacheQuery('country.list', function(){

            return DB::select("SELECT r.* FROM(SELECT d.language, d.domain, d.flag, d.slug,
                (SELECT COUNT(*) FROM universes WHERE universes.country_id=d.id AND active=1) AS num_servers
                FROM countries AS d) AS r WHERE r.num_servers > 0");

        }, 1440*7 );
    }

    static function buildStatistics($country_id=NULL){
        if( $country_id ){
            $country_id = (int) $country_id;
            $domain_extra = "u.country_id=$country_id AND ";
        } else{
            $domain_extra = '';
        }

        $sql = "SELECT
            SUM(num_players) AS num_players,
            SUM(num_alliances) AS num_alliances,
            SUM(num_planets) AS num_planets,
            SUM(num_moons) AS num_moons,
            SUM(normal_players) AS normal_players,
            SUM(inactive_players) AS inactive_players,
            SUM(inactive_30_players) AS inactive_30_players,
            SUM(outlaw_players) AS outlaw_players,
            SUM(vacation_players) AS vacation_players,
            SUM(suspended_players) AS suspended_players
            FROM meta_universes AS m
            INNER JOIN universes AS u ON u.id=m.universe_id
            WHERE $domain_extra u.active=1 AND u.api_enabled=1";

        $row = DB::selectOne($sql);
        return $row;
    }

    static function getCountry($lang){

        return self::cacheQuery('country-detail.'.$lang, function() use ( $lang ){
            $c = DB::selectOne("SELECT id, language, domain, flag
                FROM countries WHERE language=?",[$lang]);
            if(empty($c)) return NULL;
            return $c;
        }, 1440 );
    }

    static function setCurrentCountry($country){
        self::$currentCountry = $country;
    }

    static function getCurrentCountry(){
        if(self::$currentCountry==NULL){
            return new DummyObject();
        }

        return self::$currentCountry;
    }
}