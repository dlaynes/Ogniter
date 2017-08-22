<?php

namespace App\Ogniter\Maintenance\Command\Purge;

use App\Ogniter\Maintenance\Task\DeleteInactiveHighscoreTask;
use App\Ogniter\Model\Ogame\Update;
use App\Ogniter\Tools\Timer\TimerBag;
use Illuminate\Console\Command;
use DB;

class DeleteInactiveHighscoreQueue extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ogame:delete-inactive-highscore-queue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes data from missing players in the highscore table of an universe.';

    public function handle(DeleteInactiveHighscoreTask $task, TimerBag $timer)
    {

    }

}