<?php

namespace App\Http\Controllers\Classic\Server;

use App\Http\Controllers\Controller;
use App\Ogniter\Model\Ogame\Alliance;
use App\Ogniter\Model\Ogame\Country;
use App\Ogniter\Model\Ogame\Planet;
use App\Ogniter\Model\Ogame\Player;
use App\Ogniter\Model\Ogame\Universe;
use App\Ogniter\Model\Ogame\Update;
use App\Ogniter\ViewHelpers\Tags;
use Illuminate\Http\Request;

class TrackController extends Controller{

    protected $country;

    protected $universe;

    protected $limit;

    function __construct()
    {
        $this->country = Country::getCurrentCountry();

        $this->universe = Universe::getCurrentUniverse();

        $this->limit = \Config::get('ogniter.limit_planet_search');

        \View::composers([
            '\App\Http\ViewComposers\Classic\DefaultComposer'  => [
                'classic.pages.servers.track',
                'classic.pages.servers.not_found'
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

    function index(
        Planet $planetModel,
        Tags $tagsHelper,
        Update $updateModel,
           $countryLang, $universeCode, $track_type='-', $galaxy=1, $param='-', $planet_type=Planet::PLANET){

        $lang = trans();

        $data = [];
        $universe_id = $this->universe->id;

        $tagsHelper->generateLanguageSettings($lang);
        $data['tagsHelper'] = $tagsHelper;
        $data['last_update'] = $updateModel->getUpdate($universe_id, Update::UPDATE_PLANET);

        if(!in_array($planet_type, [Planet::PLANET, Planet::MOON])){
            $planet_type = Planet::PLANET; //actually it throws an error
        }

        if($track_type!='player-status'&&$track_type!='compare-alliances'&&$track_type!='compare-players'){
            $param = (int) $param;
        }
        $galaxy = (int) $galaxy;
        if($galaxy < 1){
            $galaxy = 1;
        } elseif($galaxy > $this->universe->galaxies){
            $galaxy = $this->universe->galaxies;
        }

        $limit = $this->limit;

        $data['PAGE_TITLE'] = 'Ogame - Galaxy Tools';
        $data['PAGE_DESCRIPTION'] = '';

        switch($track_type){
            case 'compare-alliances':
                $allianceModel = new Alliance();
                $ids = explode('-', $param);
                $valid_ids = array();
                $objects = array();
                $count = 0;

                foreach($ids as $id){
                    if($count > $limit){
                        break;
                    }
                    $alliance = $allianceModel->where('universe_id','=',$universe_id)
                        ->where('alliance_id', $id)->where('active','=',1)->select('alliance_id AS entity_id','name','tag')->first();
                    if(!$alliance){
                        continue;
                    }
                    $alliance->alliance_id = $alliance->entity_id; //??
                    $objects[] = $alliance;
                    $valid_ids[] = $alliance->entity_id;
                    $count++;
                }
                if(!count($objects)){
                    return \View::make('classic.pages.servers.not_found');
                }$data['PAGE_TITLE'] = $data['title'] = $lang->trans('ogniter.find_planets').' ('.$lang->trans('ogniter.og_results_by_alliance').')';
                $data['objects'] = $objects;
                $systems = $planetModel->getActivePlanets($universe_id, $galaxy, NULL, NULL, NULL, $planet_type, NULL, $valid_ids, 2);
                break;
            case 'compare-players':

                $playerModel = new Player();
                $ids = explode('-', $param);
                $valid_ids = array();
                $objects = array();
                $count = 0;
                foreach($ids as $id){
                    if($count > $limit){
                        break;
                    }
                    $player = $playerModel->where('universe_id','=',$universe_id)
                        ->where('player_id', $id)->where('active','=',1)->select('player_id AS entity_id','name')->first();
                    if(!$player){
                        continue;
                    }
                    $player->player_id = $player->entity_id; //??
                    $objects[] = $player;
                    $valid_ids[] = $player->entity_id;
                    $count++;
                }
                if(!count($objects)){
                    return \View::make('classic.pages.servers.not_found');
                }
                $data['title'] = $lang->trans('ogniter.find_planets').' ('.$lang->trans('ogniter.og_results_by_player').')';
                $data['objects'] = $objects;
                $systems = $planetModel->getActivePlanets($universe_id, $galaxy, NULL, NULL, NULL, $planet_type, NULL, $valid_ids, 1);
                break;
            case 'alliance':

                $allianceModel = new Alliance();

                $alliance = $allianceModel->getFullInfo($universe_id, $param);
                if(!$alliance){
                    return \View::make('classic.pages.servers.not_found');
                }

                $data['title'] = $lang->trans('ogniter.alliance_planets');
                $data['alliance'] = $alliance;

                $data['PAGE_TITLE'] =
                    'Ogniter. '.$lang->trans('ogniter.alliance_planets').' ('.$alliance->name.') '.$lang->trans('ogniter.og_galaxy').' '.$galaxy.' - '.$this->universe->local_name.' ('.$this->country->domain.')';
                $data['PAGE_DESCRIPTION'] = str_replace(array('%s%','%server%','%domain%'),
                    array($alliance->name, $this->universe->local_name, $this->country->domain),
                    $lang->trans('ogniter.description_server_track_alliance') );

                $systems = $planetModel->getActivePlanets($universe_id, $galaxy, $alliance->alliance_id, NULL, NULL, $planet_type);

                break;
            case 'player-status':

                $data['title'] = $lang->trans('ogniter.planet_search_by_status');

                $data['PAGE_TITLE'] = 'Ogniter. '.$lang->trans('ogniter.planet_search_by_status').(($param)?' ('.$param.') ':' ').$lang->trans('ogniter.og_galaxy').' '.$galaxy.' - '.$this->universe->local_name.' ('.$this->country->domain.')';
                $data['PAGE_DESCRIPTION'] = $lang->trans('ogniter.by_player_status').': '.(($param)?'('.$param.')':''). ' '.$lang->trans('ogniter.og_galaxy').' '.$galaxy.' - '.$this->universe->local_name.' ('.$this->country->domain.')';
                $status = Player::statusToNumber($param);
                $systems = $planetModel->getActivePlanets($universe_id, $galaxy, NULL, NULL, $status, $planet_type);
                break;

            case 'bandits-emperors':
                $data['title'] = $lang->trans('ogniter.find_bandits_emperors');

                $data['PAGE_TITLE'] = 'Ogniter. '.$lang->trans('ogniter.find_bandits_emperors').' - '.$lang->trans('ogniter.og_galaxy').' '.$galaxy.' - '.$this->universe->local_name.' ('.$this->country->domain.')';
                $data['PAGE_DESCRIPTION'] = $lang->trans('ogniter.find_bandits_emperors').' - '.$lang->trans('ogniter.og_galaxy').' '.$galaxy.' - '.$this->universe->local_name.' ('.$this->country->domain.')';

                $systems = $planetModel->getActivePlanets($universe_id, $galaxy, NULL, NULL, NULL, $planet_type, $param);
                break;
            case 'moons':

                $data['title'] = $lang->trans('ogniter.moons');

                $data['PAGE_TITLE'] = 'Ogniter. '.$lang->trans('ogniter.moons').' '.$lang->trans('ogniter.og_galaxy').' '.$galaxy.' - '.$this->universe->local_name.' ('.$this->country->domain.')';
                $data['PAGE_DESCRIPTION'] = '';

                $type = Planet::MOON;
                $systems = $planetModel->getActivePlanets($universe_id, $galaxy, NULL, NULL, NULL, $type);

                break;
            case 'free-slots':
            default:
                if($param < 0){
                    $param = 0;
                } else if($param > 15){
                    $param = 15;
                }

                $data['title'] = $lang->trans('ogniter.find_free_slots');
                $data['alliance'] = NULL;

                $data['PAGE_TITLE'] = 'Ogniter. '.$lang->trans('ogniter.find_free_slots').' '.$lang->trans('ogniter.og_galaxy').' '.$galaxy.' - '.$this->universe->local_name.' ('.$this->country->domain.')';
                $data['PAGE_DESCRIPTION'] = str_replace(array('%server%','%domain%'), array($this->universe->local_name, $this->country->domain), $lang->trans('ogniter.description_server_track_slots') );
                if($param){
                    $data['PAGE_TITLE'] .= ', '.$lang->trans('ogniter.og_location').': '.$param;
                }
                $track_type = 'free-slots';
                $systems = $planetModel->getActivePlanets($universe_id, $galaxy, NULL, $param);
                break;

        }

        $galaxy_info = array();
        if($track_type!='compare-players'&&$track_type!='compare-alliances'){
            foreach($systems as $system){
                $galaxy_info[$system->system] = $system;
            }
        } else {
            foreach($systems as $system){
                if(!isset($galaxy_info[$system->system])){
                    $galaxy_info[$system->system] = array();
                }
                $galaxy_info[$system->system][$system->entity_id] = $system;
            }
        }

        $data['type'] = (int) $planet_type;

        $data['galaxy'] = $galaxy;
        $data['param'] = $param;
        $data['galaxy_info'] = $galaxy_info;
        $data['mode'] = $track_type;

        return \View::make('classic.pages.servers.track', $data);
    }

    function doTrack(Request $request, $countryLang, $universeCode, $track_type='-', $galaxy=1, $param='-', $planet_type=Planet::PLANET){
        //Bye robots
        if($request->get('name')){
            exit();
        }
        $galaxy = (int) $request->get('galaxy');
        if($track_type=='free-slots'){
            $param = (int) $request->get('param');
        } else if($track_type=='player-status'){
            $param = implode('',(array) $request->get('filters') );
            if(!$param){ $param = '-'; }
            $planet_type = (int) $request->get('type');
        } else if($track_type=='alliance'){
            if(!$param){ $param = '-'; }
            $planet_type = (int) $request->get('type');
        } else if($track_type=='bandits-emperors'){
            $param = (int) $request->get('param');
        } else if($track_type=='compare-alliances'){
            //$type = (int) $this->input->post('type');
        } else if($track_type=='compare-players'){
            //$type = (int) $this->input->post('type');
        }
        return redirect($this->country->language.'/'.$this->universe->id.'/track/'.$track_type.'/'.$galaxy.'/'.$param.'/'.$planet_type);
    }

}