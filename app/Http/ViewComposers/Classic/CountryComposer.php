<?php

namespace App\Http\ViewComposers\Classic;

use App\Ogniter\Model\Ogame\Country;
use Illuminate\Http\Request;

class CountryComposer {

    public function __construct(Request $request)
    {
        // Dependencies automatically resolved by service container...
        $this->request = $request;
    }

    /**
     * Bind data to the view.
     *
     * @return void
     */
    public function compose( $view)
    {
        $lang = trans();

        $currentCountry = Country::getCurrentCountry();

        $method = $this->request->segment(2);
        $is_index = $method === NULL;
        $is_search = $method == 'search';
        $is_evolution = $method == 'country-evolution';

        $is_special_player = FALSE;
        $is_special_alliance = FALSE;
        $is_normal_player = FALSE;
        $is_normal_alliance = FALSE;

        $top_n_players = str_replace('%n%', 100, $lang->trans('ogniter.top_n_players') );
        $top_n_alliances = str_replace('%n%', 100, $lang->trans('ogniter.top_n_alliances') );

        $top_title = $top_n_players.' (Special)';
        $top_icon = 'icon-user';

        if($method=='top'){
            $entity = $this->request->segment(3,'players');
            $mode = $this->request->segment(5,'special');

            if($entity!='alliances'){
                $entity = 'players';
            }
            if($mode!='normal'){
                $mode = 'special';
            }
            if($entity=='players'){
                $top_icon = $breadcrumb_icon = 'icon-user';
                if($mode=='special'){
                    $is_special_player = TRUE;
                    $top_title = $breadcrumb_title = $top_n_players.' (Special)';
                } else {
                    $is_normal_player = TRUE;
                    $top_title = $breadcrumb_title = $top_n_players.' (Normal)';

                }
            } else{
                $top_icon = $breadcrumb_icon = 'icon-screenshot';
                if($mode=='special'){
                    $is_special_alliance = TRUE;
                    $top_title = $breadcrumb_title = $top_n_alliances.' (Special)';
                } else {
                    $is_normal_alliance = TRUE;
                    $top_title = $breadcrumb_title = $top_n_alliances.' (Normal)';

                }
            }
        }

        $view->with(
            [
                'top_title' => $top_title,
                'top_icon' => $top_icon,
                'countryCode' => $currentCountry->language,
                'is_index' => $is_index,
                'is_search' => $is_search,
                'is_evolution' => $is_evolution,
                'is_special_player' => $is_special_player,
                'is_special_alliance' => $is_special_alliance,
                'is_normal_player' => $is_normal_player,
                'is_normal_alliance' => $is_normal_alliance,
            ]
        );
    }
}