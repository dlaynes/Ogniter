<?php

namespace App\Http\ViewComposers\Classic;

use App\Ogniter\Model\Ogame\UniverseHistory;

class WorldStatisticsComposer {

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
        $stats = $this->universeHistoryModel->getStats($country_id=0,$universe_id=0);
        $view->with(
            [
                'statisticsLink' => 'site/evolution',
                'statisticsTitle' => trans()->trans('ogniter.world_statistics'),
                'entity' => $stats
            ]
        );
    }
}