<?php

namespace App\Ogniter\Maintenance\Command\Purge;

use App\Ogniter\Model\Ogame\Update;
use Illuminate\Console\Command;
use DB;

class DeleteInactiveAlliances extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ogame:delete-inactive-alliances';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes data from inactive alliances.';

    public function handle()
    {

    }

}