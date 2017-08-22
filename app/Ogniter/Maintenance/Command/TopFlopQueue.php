<?php

namespace App\Ogniter\Maintenance\Command;

use App\Ogniter\Model\Ogame\Update;
use Illuminate\Console\Command;
use DB;

class TopFlopQueue extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ogame:top-flop-queue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fills in the top-flop data';

    public function handle()
    {

    }

}