<?php

namespace App\Ogniter\Maintenance\Command;

use App\Ogniter\Model\Ogame\Alliance;
use App\Ogniter\Model\Ogame\Country;
use App\Ogniter\Model\Ogame\Planet;
use App\Ogniter\Model\Ogame\Player;
use App\Ogniter\Model\Ogame\Universe;
use App\Ogniter\Model\Ogame\UniverseHistory;
use App\Ogniter\Model\Ogame\UniverseMeta;
use App\Ogniter\Tools\Timer\TimerBag;
use Illuminate\Console\Command;

class DailyStats extends Command{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ogame:daily-stats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates the stats of Api-enabled Ogame Universes. Run this daily';

    public function handle(Universe $universeModel, TimerBag $timer)
    {
        $timer->addTask('daily-stats');

        $universes = $universeModel->select('id')
            ->where('active','=',1)
            ->where('api_enabled','=',1)->get();
        $date = date('Y-m-d');

        foreach($universes as $universe) {
            $universe_id = $universe->id;
            $data = [];
            $data['num_players'] = Player::countActiveInUniverse($universe_id);
            $data['num_alliances'] = Alliance::countActiveInUniverse($universe_id);

            $data['num_planets'] = Planet::countPlanets($universe_id,Planet::PLANET);
            $data['num_moons'] = Planet::countPlanets($universe_id,Planet::MOON);

            $data['normal_players'] = Player::countByStatus($universe_id, 0, '=');
            $data['inactive_players'] = Player::countByStatus($universe_id, Player::STATUS_INACTIVE, '&');
            $data['inactive_30_players'] = Player::countByStatus($universe_id, Player::STATUS_30_INACTIVE, '&');
            $data['outlaw_players'] = Player::countByStatus($universe_id, Player::STATUS_OUTLAW, '&');
            $data['vacation_players'] = Player::countByStatus($universe_id, Player::STATUS_VACATION, '&');
            $data['suspended_players'] = Player::countByStatus($universe_id, Player::STATUS_BANNED, '&');

            UniverseMeta::where('universe_id', '=', $universe_id)->update($data);
            UniverseHistory::saveHistorical(0, $universe_id, $data, $date);
        }

        $communities = Country::select('id')->where('available','=',1)->get();
        foreach($communities as $community){
            $community_stats = (array) Country::buildStatistics($community->id);
            UniverseHistory::saveHistorical($community->id, 0,$community_stats, $date);
        }

        $all_stats = (array) Country::buildStatistics();
        UniverseHistory::saveHistorical(0,0, $all_stats, $date);

        $timer->stopTask('daily-stats');
        $item = $timer->getItem('daily-stats');

        $this->comment("All universe statistics updated successfully. It took ".$item->getDifference()."s".PHP_EOL);

    }

}