<?php

namespace App\Http\Controllers\Classic\Games;

use App\Http\Controllers\Controller;

class IndexController extends Controller {


    function __construct(){

        \View::composers([
            '\App\Http\ViewComposers\Classic\DefaultComposer'  => [
                'classic.pages.games.bon-voyage'
            ]
        ]);

        \View::composers([
            '\App\Http\ViewComposers\Classic\WorldStatisticsComposer'  => [
                'classic.partials.shared.statistics'
            ]
        ]);
    }

    function bonVoyage(){
        $data = [
            'pricelist' => \Config::get('ogame.pricelist'),
            'ships' => \Config::get('ogame.ship_list'),
            'motors' => \Config::get('ogame.motors'),
            'integer_n_digits' => trans()->trans('integer_n_digits'),
            'resource' => \Config::get('ogame.tech_ids'),
            'fleet' => \Config::get('ogame.ship_list')
        ];

        \View::share($data);
        return \View::make('classic.pages.games.bon-voyage');
    }

}
