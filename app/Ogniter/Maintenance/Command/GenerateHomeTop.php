<?php

namespace App\Ogniter\Maintenance\Command;

use App\Ogniter\Model\Ogame\Update;
use Illuminate\Console\Command;
use DB;

class GenerateHomeTop extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ogame:generate-home-top';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Do not let users wait for 20 seconds until the info is displayed';

    public function handle()
    {

    }

}