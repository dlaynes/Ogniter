<?php

namespace App\Ogniter\Maintenance\Command\Records;

use App\Ogniter\Maintenance\Task\RecordsAllianceTask;
use App\Ogniter\Model\Ogame\Update;
use App\Ogniter\Tools\Timer\TimerBag;
use Illuminate\Console\Command;
use DB;

class RecordsAllianceQueue extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ogame:records-alliance-queue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates the records from alliances (pending upgrade)';

    public function handle(RecordsAllianceTask $task, TimerBag $timer)
    {

    }

}