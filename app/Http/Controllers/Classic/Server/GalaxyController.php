<?php

namespace App\Http\Controllers\Classic\Server;

use App\Http\Controllers\Controller;
use App\Ogniter\Model\Ogame\Country;
use App\Ogniter\Model\Ogame\Planet;
use App\Ogniter\Model\Ogame\PlanetChanges;
use App\Ogniter\Model\Ogame\Universe;
use App\Ogniter\Model\Ogame\UniverseHistory;
use App\Ogniter\Model\Ogame\Update;
use App\Ogniter\ViewHelpers\Tags;
use Illuminate\Http\Request;

class GalaxyController extends Controller{

    protected $country;

    protected $universe;

    function __construct()
    {
        $this->country = Country::getCurrentCountry();

        $this->universe = Universe::getCurrentUniverse();

        \View::composers([
            '\App\Http\ViewComposers\Classic\DefaultComposer'  => [
                'classic.pages.servers.galaxy'
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

    function index(UniverseHistory $statisticsHistoryModel, Update $updateModel, Tags $tagsHelper,
                   Planet $planetModel, $countryLanguage='',$universeId='', $galaxy=1, $system=1){

        $lang = trans();

        $tagsHelper->generateLanguageSettings($lang);

        $universe = $this->universe;

        $last = $updateModel->newIfNotAvailable($universe->id, Update::UPDATE_PLANET);

        //TODO: en Ogniter se leen los datos de sesion, si existen, y también se guardan
        $galaxy = $galaxy ? (int) $galaxy : 1;
        $system = $system ? (int) $system : 1;

        $data = [
            'tagsHelper' => $tagsHelper
        ];

        if($galaxy < 1){
            $galaxy = 1;
        } elseif($galaxy > $universe->galaxies){
            $galaxy = $universe->galaxies;
        }

        if($system <= 1){
            $system = 1;
            $data['prev_system'] = $universe->systems;
            $data['next_system'] = 2;
        } elseif($system >= $universe->systems){
            $system = $universe->systems;
            $data['prev_system'] = $universe->systems - 1;
            $data['next_system'] = 1;
        } else{
            $data['prev_system'] = $system-1;
            $data['next_system'] = $system+1;
        }

        $data['system'] = $system;
        $data['galaxy'] = $galaxy;

        $stats = $statisticsHistoryModel->getStats(0,$universe->id);
        $data['player_count'] = $stats->num_players;

        $data['last_update'] = $last->last_update;

        $planets = $planetModel->getPlanets($universe->id, $galaxy, $system);
        $data['planet_data'] = $planets;

        return \View::make('classic.pages.servers.galaxy', $data);
    }

    function doSearch(Request $request){

        if($request->get('name_hddn')){
            exit();
        }
        $galaxy = (int) $request->get('galaxy');
        $system = (int) $request->get('system');

        return redirect($this->country->language.'/'.$this->universe->id.'/galaxy/'.$galaxy.'/'.$system);
    }

    function ajaxPlanet(Planet $planetModel, PlanetChanges $planetChangesModel, $c, $u, $planet_id){
        $planet_id = (int) $planet_id;
        $universe_id = $this->universe->id;

        $planet = $planetModel->getPlanetDetail($universe_id, $planet_id);
        if(!$planet){
            echo '<div><h3>Planet/Moon not found!!</h3></div>';
            return;
        }

        $default_values = array(
            'name' => NULL,
            'coords' => NULL
        );
        $changes = array();

        $lang = trans();

        $planet_changes = $planetChangesModel->getChangesFrom($universe_id, $planet_id);

        //TODO: tracker
        foreach($planet_changes as $change){
            $coords = $change->gal.':'.$change->sys.':'.$change->pos;

            if(!empty($default_values['name'])){
                if($default_values['name']!=$change->name){
                    $changes[] = array(
                        'change' =>  $lang->trans('ogniter.og_name'),
                        'from' => $default_values['name'],
                        'to' => $change->name,
                        'date' => date('Y-m-d', $change->modified_on)
                    );
                    $default_values['name']=$change->name;
                }
                if($default_values['coords']!=$coords){
                    $changes[] = array(
                        'change' => $lang->trans('ogniter.og_location'),
                        'from' => $default_values['coords'],
                        'to' => $coords,
                        'date' => date('Y-m-d', $change->modified_on)
                    );
                    $default_values['coords']=$coords;
                }
            } else {
                $default_values['name'] = $change->name;
                $default_values['coords'] = $coords;
            }
        }

        $data = array(
            'lang' => $lang,
            'planet' => $planet,
            'changes' => $changes
        );
        return \View::make('classic.pages.servers.ajax_planet', $data);
    }

}