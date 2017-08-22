<?php

namespace App\Ogniter\Maintenance\Command;

use App\Ogniter\Model\Ogame\Update;
use Illuminate\Console\Command;
use DB;

class CleanUpdateErrors extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ogame:clean-update-errors';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleans the update queue from errors';

    public function handle()
    {
        $time_t = time() - 7200;

        $errors = Update::where('updating','=','1')
            ->where('updating_on','<', $time_t )->count();

        if($errors){
            DB::statement("UPDATE updates SET updating=0 WHERE updating=1
              AND updating_on < ".$time_t);
            $this->error("Error count when updating: ".$errors);
            return;
        }
        $this->comment("Ok");
    }

}