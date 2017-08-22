<?php
namespace App\Ogniter\Model\Ogame;

use App\Ogniter\Model\BaseModel;
use DB;

class Update extends BaseModel {

    protected $table = "updates";

    protected $fillable = [
        'universe_id', 'update_type_id',
        'category', 'type',
        'last_update', 'updating', 'updating_on'
    ];

    protected $primaryKey = 'id';

    const UPDATE_UNIVERSE = 1;
    const UPDATE_PLANET = 2;
    const UPDATE_ALLIANCE = 3;
    const UPDATE_PLAYER = 4;
    const UPDATE_RANKING = 5; /* Unused */
    const UPDATE_COUNTRY = 6;
    const UPDATE_RANKING_HISTORY = 7;
    const UPDATE_LOCALIZATION = 8;

    const UPDATE_TOP_FLOP = 10;
    const UPDATE_OG_PLAYER_RECORDS = 11;
    const UPDATE_OG_ALLIANCE_RECORDS = 12;
    //const UPDATE_PRANGER = 13;
    const UPDATE_HIGHSCORE_REMOVAL = 14;

    const EXPIRATION_RANKING = 21600; /* Unused in the new version of the website */
    const EXPIRATION_RANKING_HISTORY = 86400;
    const EXPIRATION_PLANET = 86400 * 7;
    const EXPIRATION_ALLIANCE = 86400;
    const EXPIRATION_PLAYER = 86400;
    const EXPIRATION_UNIVERSE = 86400;
    const EXPIRATION_COUNTRY = 86400 * 30; /* Will be done by hand instead */
    const EXPIRATION_LOCALIZATION = 86400 * 365;

    const EXPIRATION_TOP_FLOP = 86400;
    const EXPIRATION_OG_PLAYER_RECORDS = 86400;
    const EXPIRATION_OG_ALLIANCE_RECORDS = 86400;
    //const EXPIRATION_PRANGER = 86400;
    const EXPIRATION_HIGHSCORE_REMOVAL = 86400 * 30;

    function newIfNotAvailable($universe_id, $update_type_id, $category=0, $type=0){
        $query = static::where('universe_id', '=', $universe_id)
            ->where('update_type_id','=', $update_type_id);
        if($update_type_id==self::UPDATE_RANKING){
            $query->where('category','=',$category)
                ->where('type','=',$type);
        }

        $row = $query->first();
        if(!$row) {
            $row = new static();
            $row->universe_id = $universe_id;
            $row->update_type_id = $update_type_id;
            $row->category = $category;
            $row->type = $type;
            $row->last_update = 0;
            $row->updating = 0;
        }
        return $row;
    }

    function getUpdate($universe_id, $update_type_id, $category=NULL, $type=NULL){
        $sql = "SELECT last_update FROM updates WHERE universe_id=? AND update_type_id=?";
        $params = [$universe_id, $update_type_id];

        if($category!==NULL&&$type!==NULL){
            $sql .= " AND category=? AND type=?";
            $params[] = $category;
            $params[] = $type;
        }

        $update = DB::selectOne($sql, $params);
        return isset($update->last_update) ? $update->last_update : NULL;
    }

    function getUniverseForUpdate($update_type_id, $num = 1){
        $time = time();
        $query = static::select('updates.id','updates.universe_id','updates.last_update','updates.update_type_id')
            ->join('universes','universes.id','=','updates.universe_id')
            ->join('meta_universes','meta_universes.universe_id','=','universes.id')
            ->where('update_type_id','=', $update_type_id)
            ->where('universes.active','=', 1)
            ->where('universes.api_enabled','=',1)
            ->where('updating','=',0);

        switch($update_type_id){
            case Update::UPDATE_UNIVERSE:
                $query
                    ->addSelect('domain','local_name','weight','is_special')
                    ->where('updates.last_update','<', $time - self::EXPIRATION_UNIVERSE);
                break;
            case Update::UPDATE_RANKING_HISTORY:
                $query
                    ->addSelect('meta_universes.*','universes.language')
                    ->where('m.last_global_update','<', $time - self::EXPIRATION_RANKING_HISTORY);
                break;
            case Update::UPDATE_PLAYER:
                $query
                    ->addSelect('domain','universes.language')
                    ->where('updates.last_update','<', $time - self::EXPIRATION_PLAYER);
                break;
            case Update::UPDATE_PLANET:
                $query
                    ->addSelect('domain','universes.language')
                    ->where('updates.last_update','<', $time - self::EXPIRATION_PLANET);
                break;
            case Update::UPDATE_ALLIANCE:
                $query
                    ->addSelect('domain','universes.language')
                    ->where('updates.last_update','<', $time - self::EXPIRATION_ALLIANCE);
                break;
            case Update::UPDATE_HIGHSCORE_REMOVAL:
                $query
                    ->addSelect('domain','universes.language')
                    ->where('updates.last_update','<', $time - self::EXPIRATION_HIGHSCORE_REMOVAL);
                break;
            case Update::UPDATE_OG_PLAYER_RECORDS:
                $query
                    ->addSelect('domain','universes.language')
                    ->where('updates.last_update','<', $time - self::EXPIRATION_OG_PLAYER_RECORDS);
                break;
            case Update::UPDATE_OG_ALLIANCE_RECORDS:
                $query
                    ->addSelect('domain','universes.language')
                    ->where('updates.last_update','<', $time - self::EXPIRATION_OG_ALLIANCE_RECORDS);
                break;
            case Update::UPDATE_TOP_FLOP:
                $query
                    ->addSelect('domain','universes.language')
                    ->where('updates.last_update','<', $time - self::EXPIRATION_TOP_FLOP);
                break;
            default:
                throw new \Exception("Invalid type");
                break;
        }
        if($num==1){
            return $query->first();
        } else {
            return $query->take($num)->get();
        }
    }

    public static function getExpirationDelay($update_type_id){
        switch($update_type_id){
            case Update::UPDATE_UNIVERSE:
                return self::EXPIRATION_UNIVERSE + 300;
                break;
            case Update::UPDATE_RANKING_HISTORY:
                return self::EXPIRATION_RANKING_HISTORY + 600;
                break;
            case Update::UPDATE_PLAYER:
                return self::EXPIRATION_PLAYER + 300;
                break;
            case Update::UPDATE_PLANET:
                return self::EXPIRATION_PLANET + 300;
                break;
            case Update::UPDATE_ALLIANCE:
                return self::EXPIRATION_ALLIANCE + 300;
                break;
            case Update::UPDATE_RANKING:
                return 0;
            case Update::UPDATE_OG_ALLIANCE_RECORDS:
                return 0;
            case Update::UPDATE_OG_PLAYER_RECORDS:
                return 0;
            case Update::UPDATE_HIGHSCORE_REMOVAL:
                return 0;
            case Update::UPDATE_TOP_FLOP:
                return 0;
            default:
                throw new \Exception("Invalid update type: ".htmlspecialchars($update_type_id));
                break;
        }
    }

    public function initUniverseUpdateRecords($universe_id){
        $u_universe = $this->newIfNotAvailable($universe_id, self::UPDATE_UNIVERSE);
        if(!$u_universe->id){
            $u_universe->save();
        }
        $u_alliances = $this->newIfNotAvailable($universe_id,self::UPDATE_ALLIANCE);
        if(!$u_alliances->id){
            $u_alliances->save();
        }
        $u_players = $this->newIfNotAvailable($universe_id,self::UPDATE_PLAYER);
        if(!$u_players->id){
            $u_players->save();
        }
        $u_planets = $this->newIfNotAvailable($universe_id,self::UPDATE_PLANET);
        if(!$u_planets->id){
            $u_planets->save();
        }
        $u_planets = $this->newIfNotAvailable($universe_id,self::UPDATE_RANKING_HISTORY);
        if(!$u_planets->id){
            $u_planets->save();
        }
        for($c=1;$c<3;$c++){
            for($t=0;$t<8;$t++){
                $u = $this->newIfNotAvailable($universe_id,self::UPDATE_RANKING,$c,$t);
                if(!$u->id){
                    $u->save();
                }
            }
        }
        $u_top_flop = $this->newIfNotAvailable($universe_id,self::UPDATE_TOP_FLOP);
        if(!$u_top_flop->id){
            $u_top_flop->save();
        }
        $u_player_records = $this->newIfNotAvailable($universe_id,self::UPDATE_OG_PLAYER_RECORDS);
        if(!$u_player_records->id){
            $u_player_records->save();
        }
        $u_alliance_records = $this->newIfNotAvailable($universe_id,self::UPDATE_OG_ALLIANCE_RECORDS);
        if(!$u_alliance_records->id){
            $u_alliance_records->save();
        }
        $u_removal = $this->newIfNotAvailable($universe_id,self::UPDATE_HIGHSCORE_REMOVAL);
        if(!$u_removal->id){
            $u_removal->save();
        }
    }

}