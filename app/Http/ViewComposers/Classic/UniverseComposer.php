<?php

namespace App\Http\ViewComposers\Classic;

use Illuminate\Http\Request;
use App\Ogniter\Model\Ogame\Universe;

class UniverseComposer {

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Bind data to the view.
     *
     * @return void
     */
    public function compose($view)
    {
        $method = $this->request->segment(3);

        $is_index = !$method || in_array($method, ['banned_users','evolution']);
        $is_search = $method == 'search-form';
        $is_comparison = $method == 'comparison';
        $is_ranking = in_array($method, ['highscore', 'top_flop']);
        $is_tools = in_array($method, ['flight_times']);

        $universe = Universe::getCurrentUniverse();
        $lang = trans();

        $top_title_galaxy = $lang->trans('ogniter.og_galaxy');
        $top_icon_galaxy = 'icon-globe';
        $top_title_index = $universe->local_name;
        $top_icon_index = 'icon-info-sign';
        $top_title_tools = $lang->trans('ogniter.tools');
        $top_icon_tools = 'icon-wrench';
        $top_title_ranking = $lang->trans('ogniter.og_ranking');
        $top_icon_ranking = 'icon-user';

        $is_galaxy = \FALSE;
        if(in_array($method,['galaxy', 'track'])){
            do {
                if($method=='galaxy') {
                    $is_galaxy = \TRUE;
                    break;
                }
                $mode = $this->request->segment(4);
                switch($mode){
                    case 'alliance':
                        //Not in galaxy mode, sorry
                        break;
                    case 'player-status':
                        $top_title_galaxy = $lang->trans('ogniter.by_player_status');
                        $top_icon_galaxy = 'icon-question-sign';
                        $is_galaxy = \TRUE;
                        break;
                    case 'bandits-emperors':
                        $top_title_galaxy = $lang->trans('ogniter.find_bandits_emperors');
                        $top_icon_galaxy = 'icon-fast-forward';
                        $is_galaxy = \TRUE;
                        break;
                    case 'moons':
                        $top_title_galaxy = $lang->trans('ogniter.moons');
                        $top_icon_galaxy = 'icon-adjust';
                        $is_galaxy = \TRUE;
                        break;
                    default:
                        $top_title_galaxy = $lang->trans('ogniter.og_colonize');
                        $top_icon_galaxy = 'icon-map-marker';
                        $is_galaxy = \TRUE;
                        break;
                }
            } while(FALSE);
        }
        if($is_ranking){
            if($method=='top_flop'){
                $top_title_ranking = $lang->trans('ogniter.top').' / '.$lang->trans('ogniter.flop');
                $top_icon_ranking = 'icon-retweet';
            } else {
                $cat = $this->request->segment(4);
                if($cat=='players'){
                    //We don't change the title
                    $top_icon_ranking = 'icon-user';
                } else {
                    $top_icon_ranking = 'icon-screenshot';
                }
            }
        }
        if($is_index){
            switch($method){
                case 'evolution':
                    $top_title_index = 'Evolution';
                    //$top_title_index = $lang->trans('ogniter.evolution');
                    $top_icon_index = 'icon-align-right';
                    break;
                case 'banned_users':
                    $top_title_index = 'Banned users';
                    //$top_title_index = $lang->trans('ogniter.banned_users');
                    $top_icon_index = 'icon-warning-sign';
                    break;
                default:
                    break;
            }
        }
        if($is_tools){
            switch ($method){
                case 'flight_times':
                    $top_title_tools = $lang->trans('ogniter.og_flight_times');
                    $top_icon_tools = 'icon-time';
                    break;
            }
        }

        $view->with(
            [
                'top_title_index' => $top_title_index,
                'top_icon_index' => $top_icon_index,
                'top_title_tools' => $top_title_tools,
                'top_icon_tools' => $top_icon_tools,
                'top_title_galaxy' => $top_title_galaxy,
                'top_icon_galaxy' => $top_icon_galaxy,
                'top_title_ranking' => $top_title_ranking,
                'top_icon_ranking' => $top_icon_ranking,
                'is_index' => $is_index,
                'is_search' => $is_search,
                'is_comparison' => $is_comparison,
                'is_galaxy' => $is_galaxy,
                'is_ranking' => $is_ranking,
                'is_tools' => $is_tools
            ]
        );
    }
}