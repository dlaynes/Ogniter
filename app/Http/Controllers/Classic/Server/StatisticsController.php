<?php

namespace App\Http\Controllers\Classic\Server;

use App\Http\Controllers\Controller;
use App\Ogniter\Model\Ogame\Alliance;
use App\Ogniter\Model\Ogame\Country;
use App\Ogniter\Model\Ogame\Highscore;
use App\Ogniter\Model\Ogame\Player;
use App\Ogniter\Model\Ogame\Type;
use App\Ogniter\Model\Ogame\Universe;
use App\Ogniter\Tools\Strings\Verify;
use App\Ogniter\ViewHelpers\Tags;
use Illuminate\Http\Request;

class StatisticsController extends Controller{

    protected $country;

    protected $universe;

    protected $limit;

    function __construct()
    {
        $this->country = Country::getCurrentCountry();

        $this->universe = Universe::getCurrentUniverse();

        $this->limit = \Config::get('ogniter.max_comparison_values');

        \View::composers([
            '\App\Http\ViewComposers\Classic\DefaultComposer'  => [
                'classic.pages.servers.statistics'
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

    function doLimit(Request $request,
                     $currentLang='', $universeId='',$statistics_type=1, $type=0, $period='month',$from_ids=''){

        $limit = $request->get('limit');
        $until = $request->get('until');
        return redirect($currentLang.'/'.$universeId.'/statistics/'.$statistics_type.'/'.$type.'/'.$limit.':'.$until.'/'.$from_ids);
    }

    function index(Highscore $highscoreModel, Type $typeModel, Tags $tagsHelper,
                   $currentLang='', $universeId='',$statistics_type=1, $type=0, $period='month',$from_ids=''){

        $data = array();
        $universe_id = $this->universe->id;
        $language = $this->country->language;

        if(empty($from_ids)){
            \App::abort(422, 'Must specify a reference value');
        }

        $category = $statistics_type;
        $lang = trans();
        $tagsHelper->generateLanguageSettings($lang);
        $data['tagsHelper'] = $tagsHelper;

        $until = NULL;

        switch($period){
            case 'week':
                $period_name = $lang->trans('ogniter.by_week');
                $period_limit = time() - 7*3600*24;
                //$limit = mktime(1, 0, 0, date('m'), date('d')-date('w'), date('Y'));
                break;
            case 'year':
                $period_limit = time() - 365*3600*24;
                $period_name = $lang->trans('ogniter.by_year');
                //$limit = mktime(1, 0, 0, 1, 1);
                break;
            case 'all':
                $period_name = $lang->trans('ogniter.all');
                $period_limit = NULL;
                break;
            case 'month':
                $period = 'month';
                $period_name = $lang->trans('ogniter.by_month');
                $period_limit = time() - 30*3600*24;
                //$limit = mktime(1, 0, 0, date("n"), 1);
                break;
            default:
                $teh_range = explode(':', $period);

                $period_limit = Verify::processDate($teh_range[0]);
                $until = isset($teh_range[1])? Verify::processDate($teh_range[1]) : NULL;

                if($period_limit){
                    $period_name = 'Date range: '.htmlspecialchars($teh_range[0]);
                    if($until){ $period_name .= ' - '.htmlspecialchars($teh_range[1]); }
                } else {
                    $period_name = $lang->trans('ogniter.all');
                }
                break;
        }

        $data['until'] = $until;
        $data['limit'] = $period_limit;

        $data['types'] = $typeModel->getRecords();
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
        $data['category'] = $category;
        $data['type'] = $type;
        $data['from_ids'] = $from_ids;
        $data['period'] = $period;

        $cat_title = ($category==2)?$lang->trans('ogniter.og_results_by_alliance'):$lang->trans('ogniter.og_results_by_player');
        $cat_name = ($category==2)?'alliance':'player';
        $data['cat_name'] = $cat_name;
        $data['cat_title'] = $cat_title;

        $data['period_name'] = $period_name;
        $data['type_name'] = $data['dtypes'][$type];

        $from_array = explode('-', $from_ids);
        $data['statistics'] = array();

        $count = 0;
        foreach($from_array as $from_id){

            if(!$from_id){
                continue;
            }
            $from_id = (int) $from_id;
            if(isset($data['statistics'][$from_id])){
                continue;
            }

            if($count >= $this->limit){
                break;
            }

            if($category==1){
                $playerModel = new Player();
                $row = $playerModel->select('name')
                            ->where('universe_id','=',$universe_id)->where('player_id','=',$from_id)->first();
                if(!$row){
                    continue;
                }
                $data['statistics'][$from_id] = array(
                    'row' => $row,
                    'data' => $highscoreModel->getDataFrom($language, $universe_id, 1, $type, $from_id, $period_limit, $until)
                );
            } elseif($category==2){
                $allianceModel = new Alliance();
                $row = $allianceModel->select('name')
                            ->where('universe_id','=',$universe_id)->where('alliance_id','=',$from_id)->first();
                if(!$row){
                    continue;
                }
                $data['statistics'][$from_id] = array(
                    'row' => $row,
                    'data' => $highscoreModel->getDataFrom($language, $universe_id, 2, $type, $from_id, $period_limit, $until)
                );

            } else{
                $playerModel = new Player();

                $players = $playerModel->getFromAlliance($universe_id, $from_id);
                foreach($players as $player){
                    $data['statistics'][$player->player_id] = array(
                        'row' => $player,
                        'data' => $highscoreModel->getDataFrom($language, $universe_id, 1, $type, $player->player_id, $period_limit, $until)
                    );
                }
            }
            $count++;
        }

        $a = array();
        $a['PAGE_TITLE'] = $lang->trans('ogniter.title_server_statistics').', '.$data['dtypes'][$type];
        $a['PAGE_DESCRIPTION'] = $lang->trans('ogniter.description_server_statistics');

        $a = str_replace(array('%server%','%domain%'), array($this->universe->local_name, $this->country->domain), $a);

        $data['PAGE_TITLE'] = $a['PAGE_TITLE'];
        $data['PAGE_DESCRIPTION'] = $a['PAGE_DESCRIPTION'];

        return \View::make('classic.pages.servers.statistics', $data);
    }

}