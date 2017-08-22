<?php

namespace App\Http\ViewComposers\Classic;

use App\Ogniter\Model\Ogame\Country;
use App\Ogniter\Model\Ogame\UniverseHistory;

class CountryStatisticsComposer {

    protected $universeHistoryModel;

    public function __construct(UniverseHistory $universeHistory)
    {
        $this->universeHistoryModel = $universeHistory;
    }

    /**
     * Bind data to the view.
     *
     * @return void
     */
    public function compose($view)
    {
        $country = Country::getCurrentCountry();
        $stats = $this->universeHistoryModel->getStats($country->id,$universe_id=0);
        $view->with(
            [
                'statisticsLink' => $country->language.'/country-evolution',
                'statisticsTitle' => $country->domain,
                'entity' => $stats
            ]
        );
    }
}