<?php

namespace App\Http\Controllers\Classic\Site;

use App\Http\Controllers\Controller;

class ToolsController extends Controller {

    function __construct()
    {
        \View::composers([
            '\App\Http\ViewComposers\Classic\DefaultComposer'  => [
                'classic.pages.tools.flight_simulator',
                'classic.pages.tools.battle_simulator'
            ]
        ]);

        \View::composers([
            '\App\Http\ViewComposers\Classic\WorldStatisticsComposer'  => [
                'classic.partials.shared.statistics'
            ]
        ]);


    }

    function flightSimulator(){

        $data = [
            'pricelist' => \Config::get('ogame.pricelist'),
            'ships' => \Config::get('ogame.ship_list'),
            'motors' => \Config::get('ogame.motors'),
            'integer_n_digits' => trans()->trans('integer_n_digits'),
            'resource' => \Config::get('ogame.tech_ids'),
            'fleet' => \Config::get('ogame.ship_list'),
            'from' => ['','',''],
            'to' =>['','','']
        ];

        \View::share($data);
        return \View::make('classic.pages.tools.flight_simulator');
    }

    function battleSimulator(){

    }
}