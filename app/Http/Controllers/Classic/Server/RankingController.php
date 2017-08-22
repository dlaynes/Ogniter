<?php

namespace App\Http\Controllers\Classic\Server;

use App\Http\Controllers\Controller;
use App\Ogniter\Model\Ogame\Category;
use App\Ogniter\Model\Ogame\Country;
use App\Ogniter\Model\Ogame\Highscore;
use App\Ogniter\Model\Ogame\HighscoreLog;
use App\Ogniter\Model\Ogame\Type;
use App\Ogniter\Model\Ogame\Universe;
use App\Ogniter\Model\Ogame\Update;
use App\Ogniter\ViewHelpers\Tags;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class RankingController extends Controller{

    protected $country;

    protected $universe;

    function __construct()
    {
        $this->country = Country::getCurrentCountry();

        $this->universe = Universe::getCurrentUniverse();

        \View::composers([
            '\App\Http\ViewComposers\Classic\DefaultComposer'  => [
                'classic.pages.servers.ranking'
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

    /* Deprecating old route, for reasons */
    function index($countryLang, $universeCode,
                   $category=1, $type=0, $order_by='position',$order='DESC', $offset=0){

        if($category==1){
            $category_type = 'players';
        } else {
            $category_type = 'alliances';
        }
        $type = (int) $type;

        if($offset){
            $per_page = 100;
            $currentPageStr = '?page='.(round($offset/$per_page) + 1);
        } else {
            $currentPageStr = '';
        }
        return redirect($this->country->language.'/'.$this->universe->id.'/highscore/'.
            $category_type.'/'.$type.$currentPageStr, 301);
    }

    function highscore(
        Request $request, Tags $tagsHelper, Highscore $highscoreModel, Update $updateModel,
        Category $categoryModel, Type $typeModel,
        $countryLang, $universeCode,
        $category_type='players', $type=0){

        $type = (int) $type;
        //$order_by = $request->get('order_by');
        //if(!in_array($order_by,['position','weekly_difference','monthly_difference','ships'])){
            $order_by = 'position';
        //}
        //if($type!=3 && $order_by=='ships'){
        //    $order_by = 'position';
        //}
        //$order = $request->get('order');
        //if($order!='DESC') $order = 'ASC';
        $order = 'ASC';
        
        $per_page = 100;
        $currentPage = (int) $request->get('page', 1);
        if($currentPage<1) $currentPage = 1;

        $universe_id = $this->universe->id;
        $language = $this->universe->language;
        $offset = ($currentPage - 1 ) * $per_page;

        $lang = trans();
        if($category_type=='players'){
            $category = 1;
            $l_desc = $lang->trans('ogniter.og_results_by_player');
        } else {
            $category = 2;
            $l_desc = $lang->trans('ogniter.og_results_by_alliance');
        }

        $data = [];

        $tagsHelper->generateLanguageSettings($lang);
        $data['tagsHelper'] = $tagsHelper;
        $data['category_type'] = $category_type;
        $data['category'] = $category;
        $data['type'] = $type;
        $data['offset'] = $offset;

        $last_update = $updateModel->getUpdate($universe_id, Update::UPDATE_RANKING, $category, $type);

        $a = array();
        $a['PAGE_TITLE'] = $lang->trans('ogniter.title_server_ranking').' - '.$l_desc;
        $a['PAGE_DESCRIPTION'] = $lang->trans('ogniter.description_server_ranking').' - '.$l_desc;

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
        $data['PAGE_TITLE'] = $a['PAGE_TITLE'].', '.$data['dtypes'][$type];
        $data['PAGE_DESCRIPTION'] = $a['PAGE_DESCRIPTION'];

        $data['order_by'] = $order_by;
        $data['order'] = $order;
        $data['last_update'] = $last_update;

        if($this->universe->api_enabled){
            $time_comparison = time();
        } else {
            $time_comparison = HighscoreLog::getLatestRankingUpdate($universe_id, $category, $type);
        }

        $data['ranking_count'] = $highscoreModel->countList($universe_id, $category, $type);
        $data['ranking_results'] = $highscoreModel->getList($language, $universe_id, $category, $type, $per_page, $offset,
            $order_by, $order, $time_comparison );

        $path = $language.'/'.$this->universe->id.'/highscore/'.$category_type.'/'.$type;
        $pager = new LengthAwarePaginator($data['ranking_results'], $data['ranking_count'], $per_page, $currentPage);
        $pager
            ->setPath(url($path))
            ->appends([
                'order_by' => $order_by,
                'order' => $order
            ]);

        $data['pager'] = $pager;
        $data['types'] = $typeModel->getRecords();
        $data['categories'] = $categoryModel->getRecords();

        return \View::make('classic.pages.servers.ranking', $data);
    }

}