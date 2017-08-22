<?php

namespace App\Ogniter\Maintenance\Command\Purge;

use App\Ogniter\Model\Ogame\Update;
use Illuminate\Console\Command;
use DB;

class DeleteInactivePlanets extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ogame:delete-inactive-planets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes data from inactive planets';

    public function handle()
    {

    }

}