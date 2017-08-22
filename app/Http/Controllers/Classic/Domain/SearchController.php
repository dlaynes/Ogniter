<?php

namespace App\Http\Controllers\Classic\Domain;

use App\Http\Controllers\Controller;
use App\Ogniter\Model\Ogame\Country;
use App\Ogniter\Model\Ogame\Universe;
use App\Ogniter\Model\Website\Search;
use App\Ogniter\Tools\Strings\Encrypt;
use Illuminate\Http\Request;

class SearchController extends Controller {

    protected $country;

    function __construct()
    {
        $this->country = Country::getCurrentCountry();

        \View::composers([
            '\App\Http\ViewComposers\Classic\DefaultComposer'  => [
                'classic.pages.domains.search',
            ]
        ]);

        \View::composers([
            '\App\Http\ViewComposers\Classic\CountryComposer'  => [
                'classic.partials.domains.nav',
            ]
        ]);

        \View::share([
            'currentCountry' => $this->country
        ]);

        \View::composers([
            '\App\Http\ViewComposers\Classic\CountryStatisticsComposer'  => [
                'classic.partials.shared.statistics'
            ]
        ]);

    }

    function index(){
        $country = Country::getCurrentCountry();
        $data = [
            'universeList' => Universe::getUniversesFrom($country->language)
        ];

        return view('classic.pages.domains.search', $data);
    }

    function doSearch(Request $request, Universe $universeModel, Search $searchModel){

        if($request->get('name')){
            return '';
        }

        $universeId = $request->get('server');
        $universe = $universeModel->select('id','language')->where('id','=', $universeId)->first();
        if(!$universe || $universe->language != $this->country->language){
            \App::abort(404, 'Resource not found');
        }

        $s = array();
        $s['filters'] = implode('',(array) $request->get('filters') );
        $s['search_by'] = $request->get('search_by');
        $s['search'] = $request->get('search', \TRUE);
        $s['strict_search'] = $request->get('strict_search');

        $search_string = Encrypt::arrayUrlEncode($s, \TRUE);

        if($s['search']){
            //$time = time();
            //Cuando fue la última vez que se realizo una busqueda?
            //$ultima_busqueda = (int) $request->session()->get('zbz');
            //Hay un límite de 4 segundos para realizar busquedas...
            //if ($ultima_busqueda + 3 > $time) {
            //    break;
            //}

            $searchModel->register($s['search'], \TRUE, $universe->id);
            //guardar $time en zbz
        }

        return redirect($this->country->language.'/'.$universe->id.'/search-form/'.$search_string);

    }
}