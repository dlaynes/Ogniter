<?php

namespace App\Ogniter\Maintenance\Command\Deprecated;

use App\Ogniter\Model\Ogame\BannedUsers;
use App\Ogniter\Model\Ogame\Player;
use App\Ogniter\Model\Ogame\Universe;
use App\Ogniter\Model\Ogame\Update;
use App\Ogniter\Tools\Timer\TimerBag;
use Illuminate\Console\Command;
use DB;
use Schema;

class EnableOldUniverses extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ogame:enable-old-universes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Brings universes back to life';

    public function handle(Update $updateModel, Universe $universeModel, TimerBag $timer)
    {

        $qu = $universeModel->select('id','ogame_code','local_name','weight','is_special','universes.domain','universes.language')
            ->join('meta_universes AS m','m.universe_id','=','universes.id');
        $qu->where('active','=',0);
        $universes = $qu->get();

        $candidates = array();

        $timer->addTask('enable-old-universes');
        foreach($universes as $universe) {
            $language = substr($universe->ogame_code, 0, 2);
            $server_num = substr($universe->ogame_code, 2);
            $universe_id = (int)$universe->id;

            $this->comment("Reviewing universe #" . $universe_id);
            $table_name = Universe::getHighscoreTableName($universe->language, $universe_id);
            if(!Schema::hasTable($table_name)){
                $this->comment("{$table_name} table not found, skipping...");
                continue;
            }

            $p = DB::selectOne("SELECT count(*) AS c FROM planets WHERE universe_id={$universe_id} AND active=1");
            $c = DB::selectOne("SELECT count(*) AS c FROM players WHERE universe_id={$universe_id} AND active=1");
            $a = DB::selectOne("SELECT count(*) AS c FROM alliances WHERE universe_id={$universe_id} AND active=1");
            $r = DB::selectOne("SELECT count(*) AS c FROM rankings WHERE universe_id={$universe_id}");
            $h = DB::selectOne("SELECT count(*) AS c FROM {$table_name}");
            $this->comment("# of players: ".$c->c.", # of alliances: ".$a->c.", # of planets: ".$p->c);
            $this->comment("Amount of ranking data: ".$r->c.", Historical records: ".$h->c);

            if(!$p->c || !$a->c || !$c->c || !$h->c){
                $this->comment("Could not retrieve info from this universe. Skipping...");
                return;
            }
            if(!$r->c){
                DB::statement("INSERT INTO rankings
                (universe_id, entity_id, type, category, last_update, position, score, ships, active)
                  SELECT {$universe_id}, entity_id, type, category, last_update, position,	score, ships, 1
                  FROM {$table_name} WHERE last_update=(SELECT MAX(last_update) FROM {$table_name})");
            }
            DB::statement("UPDATE universes SET active=1, api_enabled=0, api_v6_enabled=0 WHERE id={$universe_id}");
            $candidates[] = $universe->language.'/'.$universe_id;
        }
        $timer->stopTask('enable-old-universes');
        $item = $timer->getItem('enable-old-universes');
        $this->comment("Enabled universes: ".implode(', ', $candidates));
        $this->comment("Task ended. It took ".$item->getDifference()."s".PHP_EOL);
    }

}