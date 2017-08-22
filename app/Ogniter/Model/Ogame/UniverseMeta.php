<?php
namespace App\Ogniter\Model\Ogame;

use App\Ogniter\Model\BaseModel;
use DB;

class UniverseMeta extends BaseModel {

    protected $table = "meta_universes";
    protected $primaryKey = "universe_id";

    protected $fillable = [
        'universe_id', 'local_name', 'show_in_global_stats',
        'weight', 'num_players', 'num_alliances',
        'num_planets', 'num_moons', 'normal_players',
        'inactive_players', 'inactive_30_players', 'outlaw_players',
        'vacation_players', 'suspended_players',
        'last_global_update',
        'previous_global_update',
        'is_special'
    ];

    public $timestamps = false;

    /*
    public function universe()
    {
        return $this->hasOne('App\Universe');
    }
    */

    public function getUniverseStatistics($universe_id, $fields=['num_players','num_alliances','num_planets',
        'num_moons','normal_players','inactive_players','inactive_30_players','outlaw_players',
        'vacation_players','suspended_players'
    ]
    ){
        return $this->select($fields)->where('universe_id','=',$universe_id)->first();
    }

    public static function getUniverseForHighscoreUpdate($timestamp_min){
        return self::select('meta_universes.*','u.language','u.domain','u.number')
            ->join('universes AS u','u.id','=','universe_id')
            ->where('u.active','=',1)
            ->where('u.api_enabled','=',1)
            ->where('last_global_update','<',$timestamp_min)->first();
    }

}