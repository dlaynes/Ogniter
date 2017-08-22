<?php

namespace App\Http\Controllers\Classic\Server;

use App\Http\Controllers\Controller;
use App\Ogniter\Model\Ogame\Country;
use DB;
use App\Ogniter\Model\Ogame\Universe;
use App\Ogniter\Model\Ogame\UniverseHistory;

class IndexController extends Controller{

    protected $country;

    protected $universe;

    function __construct()
    {
        $this->country = Country::getCurrentCountry();

        $this->universe = Universe::getCurrentUniverse();
        
        \View::composers([
            '\App\Http\ViewComposers\Classic\DefaultComposer'  => [
                'classic.pages.servers.index'
            ]
        ]);

        /**
        \View::composers([
        '\App\Http\ViewComposers\Classic\UniverseStatisticsComposer'  => [
        'classic.partials.shared.statistics'
        ]
        ]);
         */

        \View::composers([
            '\App\Http\ViewComposers\Classic\UniverseComposer'  => [
                'classic.partials.servers.nav'
            ]
        ]);

        \View::share([
            'uniShortCode' => $this->country->language.'/'.$this->universe->id,
            'currentCountry' => $this->country,
            'currentUniverse' => $this->universe
        ]);

    }

    function index(UniverseHistory $statisticsHistoryModel){

        //dd($this->universe);

        $stats = $statisticsHistoryModel->getStats(0, $this->universe->id);

        if($stats->num_players > 0){
            $percent_normal = ($stats->normal_players / $stats->num_players) * 100;
            $percent_suspended = ($stats->suspended_players / $stats->num_players) * 100;
            $percent_inactive = ($stats->inactive_players / $stats->num_players) * 100;
            $percent_inactive_30 = ($stats->inactive_30_players / $stats->num_players) * 100;
            $percent_vacation = ($stats->vacation_players / $stats->num_players) * 100;
            $percent_outlaw = ($stats->outlaw_players / $stats->num_players) * 100;
        } else {
            $percent_normal = 0;
            $percent_inactive = 0;
            $percent_inactive_30 = 0;
            $percent_vacation = 0;
            $percent_suspended = 0;
            $percent_outlaw = 0;
        }
        $data = [
            'universeStatistics' => $stats,
            'percent_normal' => $percent_normal,
            'percent_inactive' => $percent_inactive,
            'percent_inactive_30' => $percent_inactive_30,
            'percent_vacation' => $percent_vacation,
            'percent_suspended' => $percent_suspended,
            'percent_outlaw' => $percent_outlaw,
        ];
        return \View::make('classic.pages.servers.index', $data);
    }

}