<?php

namespace App\Http\Controllers\Classic\Server;

use App\Http\Controllers\Controller;
use App\Ogniter\Model\Ogame\Alliance;
use App\Ogniter\Model\Ogame\Country;
use App\Ogniter\Model\Ogame\Planet;
use App\Ogniter\Model\Ogame\Player;
use App\Ogniter\Model\Ogame\Universe;
use App\Ogniter\Model\Website\Search;
use App\Ogniter\Tools\Strings\Encrypt;
use App\Ogniter\ViewHelpers\Tags;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchController extends Controller{

    protected $country;

    protected $universe;

    function __construct()
    {
        $this->country = Country::getCurrentCountry();

        $this->universe = Universe::getCurrentUniverse();

        \View::composers([
            '\App\Http\ViewComposers\Classic\DefaultComposer'  => [
                'classic.pages.servers.search'
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

        \View::composers([
            '\App\Http\ViewComposers\Classic\SearchComposer'  => [
                'classic.partials.servers.popular_searches'
            ]
        ]);

        \View::composers([
            '\App\Http\ViewComposers\Classic\PollComposer'  => [
                'classic.partials.shared.poll_form'
            ]
        ]);

        \View::share([
            'uniShortCode' => $this->country->language.'/'.$this->universe->id,
            'currentCountry' => $this->country,
            'currentUniverse' => $this->universe
        ]);
    }

    /* Deprecating old route, for reasons */
    function index($countryLang, $universeCode, $searchString='-', $offset=0){
        if(!$searchString){
            $searchString = '-';
        }

        if($offset){
            $per_page = 20;
            $currentPageStr = '?page='.(round($offset/$per_page) + 1);
        } else {
            $currentPageStr = '';
        }
        return redirect($this->country->language.'/'.$this->universe->id.'/search-form/'.$searchString.$currentPageStr, 301);
    }

    function form(Request $request, Tags $tagsHelper, $countryLang, $universeCode, $searchString='-'){

        $per_page = 20;
        $currentPage = (int) $request->get('page', 1);
        if($currentPage<1) $currentPage = 1;

        $universe_id = $this->universe->id;
        $language = $this->country->language;
        $offset = ($currentPage - 1 ) * $per_page;

        $data = [];

        $data['strict_search'] = 1;
        $data['search_by'] = '';
        $data['search'] = '';
        $data['search_results'] = [];
        $data['search_count'] = 0;
        $data['filters'] = '';

        if($searchString=='' || $searchString=='-'){
            $searchString = '-';

            $pager = \NULL;
        } else {
            $search_query = Encrypt::arrayUrlDecode($searchString, \TRUE);

            if(isset($search_query['search_by'])){
                if(!in_array($search_query['search_by'], array('alliance','tag','player','planet'))){
                    $data['search_by'] = 'alliance';
                } else{
                    $data['search_by'] = $search_query['search_by'];
                }
            } else{
                $data['search_by'] = 'alliance';
            }
            if(!empty($search_query['search'])){
                $data['search'] = $search_query['search'];
            }
            if(empty($search_query['strict_search'])){
                $data['strict_search'] = 0;
            }

            $filters = isset($search_query['filters'])?$search_query['filters']:NULL;
            $data['filters'] = $filters;

            switch ($data['search_by']) {
                case 'alliance':
                    $allianceModel = new Alliance();

                    $data['search_count'] = $allianceModel->searchCount($universe_id, 'name', $data['search']);
                    $data['search_results'] = $allianceModel->search($universe_id, 'name', $data['search'], $per_page, $offset);
                    break;
                case 'tag':
                    $allianceModel = new Alliance();
                    $data['search_count'] = $allianceModel->searchCount($universe_id, 'tag', $data['search']);
                    $data['search_results'] = $allianceModel->search($universe_id, 'tag', $data['search'], $per_page, $offset);

                    break;
                case 'player':
                    if(!$data['strict_search']){
                        $filter_comparison = '&';
                    } else {
                        $filter_comparison = '=';
                    }
                    $playerModel = new Player();
                    $data['search_count'] = $playerModel->searchCount($universe_id, $data['search'], $filters, $filter_comparison );
                    $data['search_results'] = $playerModel->search($universe_id, $data['search'], $per_page, $offset, 'ranking_position','ASC', $filters, $filter_comparison);
                    break;
                case 'planet':
                    $planetModel = new Planet();
                    $data['search_count'] = $planetModel->searchCount($universe_id, $data['search'] );
                    $data['search_results'] = $planetModel->search($universe_id, $data['search'], $per_page, $offset);
                    break;
            }

            $path = $language.'/'.$this->universe->id.'/search-form/'.$searchString;

            $pager = new LengthAwarePaginator($data['search_results'], $data['search_count'], $per_page, $currentPage);
            $pager
                ->setPath(url($path));
            //->appends([''=>'']);
        }

        $lang = trans();
        $a = array();
        $a['PAGE_TITLE'] = $lang->trans('ogniter.title_server_search');
        $a['PAGE_DESCRIPTION'] = $lang->trans('ogniter.description_server_search');

        $a = str_replace(array('%server%','%domain%'), array($this->universe->local_name, $this->country->domain), $a);

        $data['PAGE_TITLE'] = $a['PAGE_TITLE'];
        $data['PAGE_DESCRIPTION'] = $a['PAGE_DESCRIPTION'];

        $data['searchString'] = $searchString;
        $data['pager'] = $pager;

        $tagsHelper->generateLanguageSettings($lang);
        $data['tagsHelper'] = $tagsHelper;

        return \View::make('classic.pages.servers.search', $data);
    }

    function doSearch(Search $searchModel, Request $request, $countryLang, $universeId){
        if($request->get('name_hddn')){
            /*
            $this->db->query(
                $this->db->insert_string(
                    'events', array(
                        'description' => 'Webcrawler',
                        'ip' => $_SERVER['REMOTE_ADDR'],
                        'url' => $this->uri->uri_string(),
                        'post' => htmlspecialchars(var_export($_POST, TRUE))
                    )
                )
            );
            */
            exit();
        }

        $s = array();
        $s['filters'] = implode('',(array) $request->get('filters') );
        $s['search_by'] = $request->get('search_by');
        $s['search'] = $request->get('search');
        $s['strict_search'] = $request->get('strict_search', '1');
        $search_string = Encrypt::arrayUrlEncode($s, \TRUE );

        if ($s['search']) {
            //$time = time();
            //Cuando fue la última vez que se realizo una busqueda?
            //$ultima_busqueda = (int) $this->session->userdata('zbz');
            //Hay un límite de 4 segundos para realizar busquedas...
            //if ($ultima_busqueda + 3 > $time) {
            //    break;
            //}

            $searchModel->register($s['search'], \TRUE, $this->universe->id);
        }

        return redirect($this->country->language.'/'.$this->universe->id.'/search-form/'.$search_string);
    }
}