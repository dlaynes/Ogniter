<?php

namespace App\Ogniter\Maintenance\Command;

use App\Ogniter\Model\Ogame\Universe;
use App\Ogniter\Model\Ogame\Update;
use Illuminate\Console\Command;
use DB;

class ToggleUniverse extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ogame:toggle-universe';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enables or disables the Api on an Universe';


    /**
     * The console command signature
     *
     * @var string
     */
    protected $signature = 'ogame:toggle-universe {universe_id}';

    public function handle(Universe $universeModel)
    {
        $universe = $universeModel->select('id','api_enabled')
            ->where('id','=', (int) $this->argument('universe_id'))->first();

        if(!$universe){
            $this->error("Universe not found");
        }

        $universe->api_enabled = (int) !$universe->api_enabled;
        $universe->save();

        $this->comment("Api Status of universe #".$universe->id.": ".( $universe->api_enabled?'Enabled':'Disabled'));
    }

}