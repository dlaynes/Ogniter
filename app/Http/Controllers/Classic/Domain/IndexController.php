<?php

namespace App\Http\Controllers\Classic\Domain;

use App\Http\Controllers\Controller;
use App\Ogniter\Model\Ogame\Country;
use App\Ogniter\Model\Ogame\Universe;

class IndexController extends Controller {

    function __construct()
    {

        \View::composers([
            '\App\Http\ViewComposers\Classic\DefaultComposer'  => [
                'classic.pages.domains.index',
            ]
        ]);

        \View::composers([
            '\App\Http\ViewComposers\Classic\CountryComposer'  => [
                'classic.partials.domains.nav',
            ]
        ]);

        \View::share([
            'currentCountry' => Country::getCurrentCountry()
        ]);

        \View::composers([
            '\App\Http\ViewComposers\Classic\CountryStatisticsComposer'  => [
                'classic.partials.shared.statistics'
            ]
        ]);

    }

    function index(){
        $lang = trans();

        $country = Country::getCurrentCountry();
        $data = [
            'universeList' => Universe::getUniversesFrom($country->language),
            'l_search' => $lang->trans('ogniter.og_search'),
            'l_galaxy' => $lang->trans('ogniter.og_galaxy'),
            'l_galaxy_view' => $lang->trans('ogniter.og_galaxy_view'),
            'l_ranking' => $lang->trans('ogniter.og_ranking'),
            'l_colonize' => $lang->trans('ogniter.og_colonize'),
            'l_bandits' => $lang->trans('ogniter.find_bandits_emperors'),
            'l_status_search' => $lang->trans('ogniter.planet_search_by_status'),
            'l_players' => $lang->trans('ogniter.og_player'),
            'l_alliances' => $lang->trans('ogniter.og_alliance')
        ];

        return view('classic.pages.domains.index', $data);
    }
}