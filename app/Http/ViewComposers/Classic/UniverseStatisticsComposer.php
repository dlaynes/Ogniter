<?php

namespace App\Http\ViewComposers\Classic;

use App\Ogniter\Model\Ogame\Country;
use App\Ogniter\Model\Ogame\Universe;
use App\Ogniter\Model\Ogame\UniverseHistory;

class UniverseStatisticsComposer {

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
        $universe = Universe::getCurrentUniverse();
        $stats = $this->universeHistoryModel->getStats(0,$universe->id);
        $view->with(
            [
                'statisticsLink' => $country->language.'/'.$universe->id.'/evolution',
                'statisticsTitle' => $universe->local_name,
                'entity' => $stats
            ]
        );
    }
}