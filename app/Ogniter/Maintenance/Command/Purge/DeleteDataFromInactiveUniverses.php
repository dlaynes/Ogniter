<?php

namespace App\Ogniter\Maintenance\Command\Purge;

use App\Ogniter\Model\Ogame\Update;
use Illuminate\Console\Command;
use DB;

class DeleteDataFromInactiveUniverses extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ogame:delete-data-from-inactive-universes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes data from inactive universes. Disable the universe instead.';

    public function handle()
    {

    }

}