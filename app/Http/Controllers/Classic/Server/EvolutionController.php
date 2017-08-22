<?php

namespace App\Http\Controllers\Classic\Server;

use App\Http\Controllers\Controller;
use App\Ogniter\Model\Ogame\Country;
use App\Ogniter\Model\Ogame\Universe;
use App\Ogniter\Model\Ogame\UniverseHistory;
use Illuminate\Http\Request;

class EvolutionController extends Controller
{

    protected $country;

    protected $universe;

    function __construct()
    {
        $this->country = Country::getCurrentCountry();

        $this->universe = Universe::getCurrentUniverse();

        \View::composers([
            '\App\Http\ViewComposers\Classic\DefaultComposer' => [
                'classic.pages.servers.evolution'
            ]
        ]);

        \View::composers([
            '\App\Http\ViewComposers\Classic\UniverseComposer' => [
                'classic.partials.servers.nav'
            ]
        ]);

        \View::share([
            'uniShortCode' => $this->country->language . '/' . $this->universe->id,
            'currentCountry' => $this->country,
            'currentUniverse' => $this->universe
        ]);
    }

    function index(Request $request, UniverseHistory $statisticsHistoryModel)
    {
        $from = $request->get('from');
        $to = $request->get('to');

        $stats = UniverseHistory::formatStats($statisticsHistoryModel->getAllStats(0,$this->universe->id, $from, $to));

        $lang = trans();

        $descUsers =
            [
                ['data'=>$stats['users_data'], 'label'=>$lang->trans('ogniter.og_num_players'),
                    'lines'=>['show'=>TRUE,'fill'=>TRUE],'points'=>['show'=>TRUE]],
                ['data'=>$stats['alliances_data'], 'label'=>$lang->trans('ogniter.og_num_alliances'),
                    'lines'=>['show'=>TRUE,'fill'=>TRUE],'points'=>['show'=>TRUE]]
            ];
        $descPlanets =
            [
                ['data'=>$stats['planets_data'], 'label'=>$lang->trans('ogniter.planets'),
                    'lines'=>['show'=>TRUE,'fill'=>TRUE],'points'=>['show'=>TRUE]],
                ['data'=>$stats['moons_data'], 'label'=>$lang->trans('ogniter.moons'),
                    'lines'=>['show'=>TRUE,'fill'=>TRUE],'points'=>['show'=>TRUE]]
            ];
        $descStatus =
            [
                ['data'=>$stats['normal_players_data'], 'label'=>$lang->trans('ogniter.normal'),
                    'lines'=>['show'=>TRUE,'fill'=>TRUE],'points'=>['show'=>TRUE]],
                ['data'=>$stats['inactive_players_data'], 'label'=>$lang->trans('ogniter.og_inactive'),
                    'lines'=>['show'=>TRUE,'fill'=>TRUE],'points'=>['show'=>TRUE]],
                ['data'=>$stats['inactive_30_players_data'], 'label'=>$lang->trans('ogniter.og_inactive_30'),
                    'lines'=>['show'=>TRUE,'fill'=>TRUE],'points'=>['show'=>TRUE]],
                ['data'=>$stats['outlaw_players_data'], 'label'=>$lang->trans('ogniter.og_outlaw'),
                    'lines'=>['show'=>TRUE,'fill'=>TRUE],'points'=>['show'=>TRUE]],
                ['data'=>$stats['vacation_players_data'], 'label'=>$lang->trans('ogniter.og_v_mode'),
                    'lines'=>['show'=>TRUE,'fill'=>TRUE],'points'=>['show'=>TRUE]],
                ['data'=>$stats['suspended_players_data'], 'label'=>$lang->trans('ogniter.og_suspended'),
                    'lines'=>['show'=>TRUE,'fill'=>TRUE],'points'=>['show'=>TRUE]],
        ];

        $data = [
            'descUsers' => $descUsers,
            'descPlanets' => $descPlanets,
            'descStatus' => $descStatus,
            'PAGE_TITLE' => 'Ogniter - Evolution: '.$this->universe->local_name.' ('.$this->country->domain.')',
            'PAGE_DESCRIPTION' => 'Players & planets evolution in '.$this->universe->local_name.' ('.$this->country->domain.')'
        ];
        return view('classic.pages.servers.evolution', $data);
    }


}