<?php

namespace App\Http\Controllers\Classic\Server;

use App\Http\Controllers\Controller;
use App\Ogniter\Model\Ogame\Country;
use App\Ogniter\Model\Ogame\Highscore;
use App\Ogniter\Model\Ogame\HighscoreLog;
use App\Ogniter\Model\Ogame\Universe;
use App\Ogniter\Model\Ogame\UniverseHistory;
use App\Ogniter\ViewHelpers\Tags;
use Illuminate\Http\Request;

class TopFlopController extends Controller
{

    protected $country;

    protected $universe;

    function __construct()
    {
        $this->country = Country::getCurrentCountry();

        $this->universe = Universe::getCurrentUniverse();

        \View::composers([
            '\App\Http\ViewComposers\Classic\DefaultComposer' => [
                'classic.pages.servers.top_flop'
            ]
        ]);

        \View::composers([
            '\App\Http\ViewComposers\Classic\UniverseStatisticsComposer'  => [
                'classic.partials.shared.statistics'
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

    function index(Highscore $highscoreModel, Tags $tagsHelper, Request $request,
                   $currentLang='', $universeId='', $order='DESC', $category=1,$type=0)
    {
        $data = array();

        $range = $request->get('range', 'by_day');
        if(!in_array($range,['by_day','by_week','by_month'])){
            $range = 'by_day';
        }

        $per_page = 20;
        $universe_id = $this->universe->id;
        $language = $this->universe->language;

        $lang = trans();
        $tagsHelper->generateLanguageSettings($lang);
        $data['tagsHelper'] = $tagsHelper;
        
        if($category!=2){
            $category=1;
            $l_desc = $lang->trans('ogniter.og_results_by_player');

            if(!in_array($type, array('0','3'))){ $type=0; }
            $category_name = 'players';
        } else{
            $type = 0;
            $l_desc = $lang->trans('ogniter.og_results_by_alliance');

            $category_name = 'alliances';
        }

        $order = ($order=='DESC')?'DESC':'ASC';
        
        $last_server_update = HighscoreLog::getLatestRankingUpdate($universe_id,$category,$type);
        switch ($range){
            case 'by_month':
                $after_time = $last_server_update - 30 * 86400;
                $previous_server_update = HighscoreLog::getRankingUpdateNear($universe_id, $after_time, $category, $type);
                $range_desc = $lang->trans('ogniter.by_month');
                break;
            case 'by_week':
                $after_time = $last_server_update - 7 * 86400;
                $previous_server_update = HighscoreLog::getRankingUpdateNear($universe_id, $after_time, $category, $type);
                $range_desc = $lang->trans('ogniter.by_week');
                break;
            case 'by_day':
                $previous_server_update = HighscoreLog::getPreviousRankingUpdate($universe_id,$last_server_update,$category,$type);
                $range_desc = $lang->trans('ogniter.by_day');
                break;
            default:
                $previous_server_update = HighscoreLog::getPreviousRankingUpdate($universe_id,$last_server_update,$category,$type);
                $range_desc = $lang->trans('ogniter.by_day');
                break;
        }

        $top_flop = $highscoreModel->getTopFlop(
            $language,$universe_id, $category,$type,$last_server_update,$previous_server_update,$order,$per_page, $range);

        if($order!=='DESC'){
            $data['tf_desc'] = ($category==1)?$lang->trans('ogniter.tf_flop_n_players'):$lang->trans('ogniter.tf_flop_n_alliances');
        } else {
            $data['tf_desc'] = ($category==1)?$lang->trans('ogniter.tf_top_n_players'):$lang->trans('ogniter.tf_top_n_alliances');
        }

        $data['range'] = $range;
        $data['range_desc'] = $range_desc;
        $data['order'] = $order;
        $data['per_page'] = $per_page;
        $data['last_server_update'] = $last_server_update;
        $data['previous_server_update'] = $previous_server_update;

        $a = array();
        $a['PAGE_TITLE'] = 'Ogniter. '.$lang->trans('ogniter.top_flop').' - '.$l_desc.', '.$range_desc;
        $a['PAGE_DESCRIPTION'] = $lang->trans('ogniter.top_flop_description').' - '.$l_desc;
        $data['PAGE_KEYWORDS'] = 'ogame,statistics';

        $a = str_replace(array('%server%','%domain%'), array($this->universe->local_name, $this->country->domain), $a);

        $data['dtypes'] = [
            $lang->trans('ogniter.og_total'),
            $lang->trans('ogniter.og_economy'),
            $lang->trans('ogniter.og_research'),
            $lang->trans('ogniter.og_mil_points'),
            $lang->trans('ogniter.og_lost_mil_points'),
            $lang->trans('ogniter.og_built_mil_points'),
            $lang->trans('ogniter.og_destroyed_mil_points'),
            $lang->trans('ogniter.og_honor'),
        ];
        $data['type_name'] = $data['dtypes'][$type];
        $data['category_name'] = $category_name;

        $data['PAGE_TITLE'] = $a['PAGE_TITLE'].', '.$data['type_name'];
        $data['PAGE_DESCRIPTION'] = $a['PAGE_DESCRIPTION'];
        $data['top_flop'] = $top_flop;
        $data['category'] = $category;
        $data['type'] = $type;

        return \View::make('classic.pages.servers.top_flop', $data);
    }

}