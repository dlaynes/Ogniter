<?php

namespace App\Http\ViewComposers\Classic;

use App\Ogniter\Model\Ogame\Universe;
use App\Ogniter\Model\Website\Search;

class SearchComposer {

    protected $searchModel;

    public function __construct(Search $searchModel)
    {
        $this->searchModel= $searchModel;
        $this->universe = Universe::getCurrentUniverse();
    }

    /**
     * Bind data to the view.
     *
     * @return void
     */
    public function compose($view)
    {
        $view->with(
            [
                'popular' => $this->searchModel->mostPopular($this->universe->id),
            ]
        );
    }
}