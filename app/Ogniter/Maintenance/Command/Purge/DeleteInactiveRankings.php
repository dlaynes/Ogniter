<?php

namespace App\Ogniter\Maintenance\Command\Purge;

use App\Ogniter\Model\Ogame\Update;
use Illuminate\Console\Command;
use DB;

class DeleteInactiveRankings extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ogame:delete-inactive-rankings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes data from inactive records in the rankings table.';

    public function handle()
    {

    }

}