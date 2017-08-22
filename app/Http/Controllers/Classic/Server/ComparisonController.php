<?php

namespace App\Http\Controllers\Classic\Server;

use App\Http\Controllers\Controller;
use App\Ogniter\Model\Ogame\Alliance;
use App\Ogniter\Model\Ogame\Country;
use App\Ogniter\Model\Ogame\Player;
use App\Ogniter\Model\Ogame\Universe;
use Illuminate\Http\Request;

class ComparisonController extends Controller
{

    protected $country;

    protected $universe;

    protected $limit_comparison;

    protected $limit_planet_search;

    function __construct()
    {
        $this->country = Country::getCurrentCountry();

        $this->universe = Universe::getCurrentUniverse();

        $this->limit_comparison = \Config::get('ogniter.max_comparison_values');

        $this->limit_planet_search = \Config::get('ogniter.max_planet_search_values');
        
        \View::composers([
            '\App\Http\ViewComposers\Classic\DefaultComposer' => [
                'classic.pages.servers.comparison'
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

    function index()
    {

        $lang = trans();
        
        $a = array();
        $a['PAGE_TITLE'] = $lang->trans('ogniter.title_server_comparison');
        $a['PAGE_DESCRIPTION'] = $lang->trans('ogniter.description_server_comparison');

        $a = str_replace(array('%server%','%domain%'), array($this->universe->local_name, $this->country->domain), $a);

        $data = [
            'limit' => $this->limit_planet_search,
            'limit_comparison' => $this->limit_comparison,
            'PAGE_TITLE' => $a['PAGE_TITLE'],
            'PAGE_DESCRIPTION' => $a['PAGE_DESCRIPTION']
        ];
        return view('classic.pages.servers.comparison', $data);
    }

    function doComparison(Request $request){

        if($request->get('name_hddn')){
            exit();
        }
        if($request->get('search_by_alliance') || $request->get('search_by_player') ){
            $ids = $this->autoCompleteIds($request, 'by_alliance','by_player', $this->limit_comparison);
            if($ids){
                $category = $request->get('search_by_alliance')?'2':'1';
                return redirect($this->country->language.'/'.$this->universe->id.'/statistics/'.$category.'/0/month/'.implode('-', $ids));
            }
        } else if($request->get('planet_search_by_alliance') || $request->get('planet_search_by_player')){
            $ids = $this->autoCompleteIds($request,'planet_by_alliance','planet_by_player', $this->limit_planet_search);
            if($ids){
                $where = $request->get('planet_search_by_player')?'compare-players':'compare-alliances';
                return redirect($this->country->language.'/'.$this->universe->id.'/track/'.$where.'/1/'.implode('-', $ids).'/1');
            }
        }
        return redirect($this->country->language.'/'.$this->universe->id.'/comparison');
    }

    protected function autoCompleteIds(Request $request, $opt_alliance_post, $opt_player_post, $stop=NULL){
        $ids = array();
        $count = 1;

        if($request->get('search_by_alliance')||$request->get('planet_search_by_alliance')){
            $model = new Alliance();
            $names = explode(',', $request->get($opt_alliance_post));
            $fields = 'alliance_id';

        } else{
            $model = new Player();
            $names = explode(',', $request->get($opt_player_post));
            $fields = 'player_id';
        }
        foreach($names as $name){
            if(!is_null($stop) && $count > $stop){
                break;
            }
            $universe_id = $this->universe->id;
            $row = $model->where('name','=', $name)->where('universe_id','=', $universe_id)
                ->where('active','=',1)->select($fields)->first();
            if(!empty($row->{$fields})){
                $ids[] = $row->{$fields};
                $count++;
            }
        }
        return $ids;
    }

    function ajaxSearch(Request $request, $countryLang, $universeId, $category){
        $universe_id = $this->universe->id;
        $json = array();

        if(count($_POST)){
            $search = $request->get('search');

            $results = array();
            switch ($category) {
                case '1':
                    $model = new Player();
                    $rows = $model->tagAutoComplete('name', $search, 'name', 'AND universe_id='.$universe_id.' AND active=1');
                    break;
                case '2':
                default:
                    $model = new Alliance();
                    $rows = $model-> tagAutoComplete('name', $search, 'name', 'AND universe_id='.$universe_id.' AND active=1');
                    break;
            }
            foreach($rows as $row){
                $results[] = $row->name;
            }
            $json['choices'] = $results;

            //prevent xss '??????
            echo 'while(1);'.json_encode($json);
        }
        exit();
    }


}