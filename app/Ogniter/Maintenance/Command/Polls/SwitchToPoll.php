<?php

namespace App\Ogniter\Maintenance\Command\Polls;

use Illuminate\Console\Command;
use DB;
use App\Ogniter\Model\Website\Poll;

class SwitchToPoll extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ogame:poll-switch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleans the update queue from errors';

    protected $signature = 'ogame:poll-switch {poll_id}';

    public function handle(Poll $pollModel)
    {
        $poll = $pollModel->getResults($this->argument('poll_id'));
        if(!$poll){
            $this->error("Poll not found");
            return;
        }
        $pollModel->disablePolls();
        $pollModel->enablePoll($poll->id);
        $this->info("Poll #".$poll->id." enabled succesfully");
    }
}