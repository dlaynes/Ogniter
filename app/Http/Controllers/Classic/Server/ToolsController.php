<?php

namespace App\Http\Controllers\Classic\Server;

use App\Http\Controllers\Controller;
use App\Ogniter\Model\Ogame\Country;
use App\Ogniter\Model\Ogame\Universe;

class ToolsController extends Controller {

    protected $country;

    protected $universe;

    function __construct()
    {
        $this->country = Country::getCurrentCountry();

        $this->universe = Universe::getCurrentUniverse();

        \View::composers([
            '\App\Http\ViewComposers\Classic\DefaultComposer'  => [
                'classic.pages.servers.flight_simulator'
            ]
        ]);
        
        \View::composers([
            '\App\Http\ViewComposers\Classic\UniverseStatisticsComposer'  => [
                'classic.partials.shared.statistics'
            ]
        ]);

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

    function flightSimulator($countryLang,$universeId,$from_str='-',$to_str='-'){

        $max_system = 17;

        $from_array = array('','','');
        do{
            if(!$from_str && $from_str!='-'){
                break;
            }
            $from = explode(':',$from_str);
            if(!isset($from[1], $from[2])){
                break;
            }
            $galaxy = (int) $from[0];
            $system = (int) $from[1];
            $position = (int) $from[2];

            if($galaxy < 1){
                $galaxy = 1;
            } elseif($galaxy > $this->universe->galaxies){
                $galaxy = $this->universe->galaxies;
            }
            if($system <= 1){
                $system = 1;
            } elseif($system >= $this->universe->systems){
                $system = $this->universe->systems;
            }
            if($position <= 1){
                $position = 1;
            } elseif($system > $max_system){
                $position= $max_system;
            }
            $from_array = array($galaxy, $system, $position);
        } while(FALSE);

        $to_array = array('','','');
        do{
            if(!$to_str && $to_str!='-'){
                break;
            }
            $to = explode(':',$to_str);
            if(!isset($to[1], $to[2])){
                break;
            }
            $galaxy = (int) $to[0];
            $system = (int) $to[1];
            $position = (int) $to[2];
            if($galaxy < 1){
                $galaxy = 1;
            } elseif($galaxy > $this->universe->galaxies){
                $galaxy = $this->universe->galaxies;
            }
            if($system <= 1){
                $system = 1;
            } elseif($system >= $this->universe->systems){
                $system = $this->universe->systems;
            }
            if($position <= 1){
                $position = 1;
            } elseif($system > $max_system){
                $position= $max_system;
            }
            $to_array = array($galaxy, $system, $position);
        } while(FALSE);

        $lang = trans();

        $data = [
            'PAGE_TITLE' => 'Ogniter - '.$lang->trans('ogniter.flight_time_calculator').' - '.$this->universe->local_name.' ('.$this->country->domain.')',
            'PAGE_DESCRIPTION' => $lang->trans('ogniter.description_fleet_simulator_module').' - '.$this->universe->local_name.' ('.$this->country->domain.')',
            'pricelist' => \Config::get('ogame.pricelist'),
            'ships' => \Config::get('ogame.ship_list'),
            'motors' => \Config::get('ogame.motors'),
            'integer_n_digits' => trans()->trans('integer_n_digits'),
            'resource' => \Config::get('ogame.tech_ids'),
            'fleet' => \Config::get('ogame.ship_list'),
            'from' => $from_array,
            'to' => $to_array
        ];

        \View::share($data);
        return \View::make('classic.pages.servers.flight_simulator');
    }

    function battleSimulator(){

    }
}