<?php

namespace App\Http\ViewComposers\Classic;

use App\Ogniter\Model\Website\Poll;

class PollComposer {

    protected $pollModel;

    public function __construct(Poll $pollModel)
    {
        $this->pollModel = $pollModel;
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
                'currentPoll' => $this->pollModel->getLatestPoll(),
            ]
        );
    }
}