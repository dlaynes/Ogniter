<?php
namespace App\Ogniter\Model\Ogame;

use App\Ogniter\Model\BaseModel;
use DB;

class UniverseHistory extends BaseModel
{

    protected $table = "statistics_history";

    /* Intentionally, we store the country_id as zero, if the universe id is set
    Also, the universe id should be zero for country-only stats
    For the global stats, both values should be zero
     */
    public static function saveHistorical($country_id, $universe_id, $data, $date){

        $country_id = (int) $country_id;
        $universe_id = (int) $universe_id;
        
        $sql = "REPLACE INTO statistics_history
            SET country_id=$country_id,
            universe_id=$universe_id,
            num_players=".((int) $data['num_players']).",
            num_alliances=".((int) $data['num_alliances']).",
            num_planets=".((int) $data['num_planets']).",
            num_moons=".((int) $data['num_moons']).",
            normal_players=".((int) $data['normal_players']).",
            inactive_players=".((int) $data['inactive_players']).",
            inactive_30_players=".((int) $data['inactive_30_players']).",
            outlaw_players=".((int) $data['outlaw_players']).",
            vacation_players=".((int) $data['vacation_players']).",
            suspended_players=".((int) $data['suspended_players']).",
            added_on=?";

        DB::statement($sql, [$date]);
    }

    public function getStats($country_id=0, $universe_id=0){
        $qu = self::select('num_players','num_alliances','num_planets','num_moons','normal_players',
            'inactive_players','inactive_30_players','outlaw_players','vacation_players','suspended_players','added_on')
            ->where('country_id','=',$country_id)
            ->where('statistics_history.universe_id','=',$universe_id)->orderBy('added_on','DESC');
        $row = $qu->first();

        if(!$row){
            //Stats not ready yet!
            $row = (object) [
                'num_players' => 0,
                'num_alliances' => 0,
                'num_planets' => 0,
                'num_moons' => 0,
                'normal_players' => 0,
                'inactive_players' => 0,
                'inactive_30_players' => 0,
                'outlaw_players' => 0,
                'vacation_players' => 0,
                'suspended_players' => 0,
                'added_on' => date('Y-m-d')
            ];
        }
        return $row;
    }

    public function getAllStats($country_id=0, $universe_id=0, $from_date=NULL, $to_date=NULL){
        return self::select('num_players','num_alliances','num_planets','num_moons','normal_players',
            'inactive_players','inactive_30_players','outlaw_players','vacation_players','suspended_players','added_on')
            ->where('country_id','=',$country_id)
            ->where('universe_id','=',$universe_id)->orderBy('added_on','ASC')->get();
    }

    public static function formatStats($statistics){

        $data = [
            'users_data' => [],
            'alliances_data' => [],
            'planets_data' => [],
            'moons_data' => [],
            'normal_players_data' => [],
            'inactive_players_data' => [],
            'inactive_30_players_data' => [],
            'outlaw_players_data' => [],
            'vacation_players_data' => [],
            'suspended_players_data' => [],
        ];

        $min_last_update = 0;
        $max_last_update = 0;

        foreach($statistics as $rw){

            $created_on = strtotime($rw->added_on);
            if($min_last_update){
                if($created_on <  $min_last_update){
                    $min_last_update = $created_on;
                }
            } else{
                $min_last_update = $created_on;
            }
            if($max_last_update){
                if($created_on >  $max_last_update){
                    $max_last_update = $created_on;
                }
            } else{
                $max_last_update = $created_on;
            }
            $mil_created_on = $created_on*1000;

            $data['users_data'][] = [$mil_created_on, $rw->num_players];
            $data['alliances_data'][] = [$mil_created_on, $rw->num_alliances];
            $data['planets_data'][] = [$mil_created_on, $rw->num_planets];
            $data['moons_data'][] = [$mil_created_on, $rw->num_moons];

            $data['normal_players_data'][] = [$mil_created_on, $rw->normal_players];
            $data['inactive_players_data'][] = [$mil_created_on, $rw->inactive_players];
            $data['inactive_30_players_data'][] = [$mil_created_on, $rw->inactive_30_players];
            $data['outlaw_players_data'][] = [$mil_created_on, $rw->outlaw_players];
            $data['vacation_players_data'][] = [$mil_created_on, $rw->vacation_players];
            $data['suspended_players_data'][] = [$mil_created_on, $rw->suspended_players];
        }

        $data['min_last_update'] = $min_last_update;
        $data['max_last_update'] = $max_last_update;

        return $data;
    }

}