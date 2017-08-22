<?php

namespace App\Http\Controllers\Classic\Domain;

use App\Http\Controllers\Controller;
use App\Ogniter\Model\Ogame\Alliance;
use App\Ogniter\Model\Ogame\Category;
use App\Ogniter\Model\Ogame\Country;
use App\Ogniter\Model\Ogame\Player;
use App\Ogniter\Model\Ogame\Type;

class TopController extends Controller {

    protected $country;

    function __construct()
    {
        $this->country = Country::getCurrentCountry();

        \View::composers([
            '\App\Http\ViewComposers\Classic\DefaultComposer' => [
                'classic.pages.domains.top'
            ]
        ]);

        \View::composers([
            '\App\Http\ViewComposers\Classic\CountryComposer' => [
                'classic.partials.domains.nav'
            ]
        ]);

        \View::composers([
            '\App\Http\ViewComposers\Classic\CountryStatisticsComposer'  => [
                'classic.partials.shared.statistics'
            ]
        ]);

        \View::share('currentCountry', $this->country);
    }

    function top(Type $typeModel, Category $categoryModel, $countryCode='', $category_type='players',$type=0, $mode='normal'){
        $lang = trans();
        $limit = 100;

        if($category_type=='players'){
            $category = 1;

            $records = Player::topPlayers($limit, $type, $this->country->id, $mode);
            $page_title = str_replace('%n%', $limit, $lang->trans('ogniter.title_top_n_players') );
            $page_description = str_replace('%n%', $limit, $lang->trans('ogniter.description_top_n_players') );
            $module_name = str_replace('%n%', 100,$lang->trans('ogniter.top_n_players') );
        } else {
            $category = 2;

            $page_title = str_replace('%n%', $limit, $lang->trans('ogniter.title_top_n_alliances') );
            $page_description =str_replace('%n%', $limit, $lang->trans('ogniter.description_top_n_players') );
            $records = Alliance::topAlliances($limit, $type, $this->country->id, $mode);
            $module_name = str_replace('%n%', 100,$lang->trans('ogniter.top_n_alliances') );
        }

        $lang_types = [
            $lang->trans('ogniter.og_total'),
            $lang->trans('ogniter.og_economy'),
            $lang->trans('ogniter.og_research'),
            $lang->trans('ogniter.og_mil_points'),
            $lang->trans('ogniter.og_lost_mil_points'),
            $lang->trans('ogniter.og_built_mil_points'),
            $lang->trans('ogniter.og_destroyed_mil_points'),
            $lang->trans('ogniter.og_honor'),
        ];

        $data = [
            'cat_name' => $category_type,
            'page_title' => $page_title,
            'page_description' => $page_description,
            'module_name' => $module_name,
            'categories' => $categoryModel->getRecords(),
            'types' => $typeModel->getRecords(),
            'limit' => $limit,
            'category' => $category,
            'type' => $type,
            'mode' => $mode,
            'lang_types' => $lang_types,
            'records' => $records,
            'is_normal_player' => $mode=='normal'&&$category==1,
            'is_normal_alliance' => $mode=='normal'&&$category==2,
            'is_special_player' => $mode=='special'&&$category==1,
            'is_special_alliance' => $mode=='special'&&$category==2
        ];

        return \View::make('classic.pages.domains.top', $data);

    }

    function topPlanets(){

    }

}