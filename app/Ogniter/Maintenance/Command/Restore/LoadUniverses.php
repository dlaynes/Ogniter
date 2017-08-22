<?php

namespace App\Ogniter\Maintenance\Command\Restore;

use Illuminate\Console\Command;

use App\Ogniter\Model\Ogame\Country;

use App\Ogniter\Model\Ogame\Universe;
use App\Ogniter\Model\Ogame\UniverseMeta;

use App\Ogniter\Model\Ogame\Update;

use App\Ogniter\Tools\Timer\TimerBag;


class LoadUniverses extends Command {

	public $url = 'https://api.ogniter.org/api/';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ogniter:load-universes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restores previously created universes';


    public function handle()
    {
        $time_t = time();

        $communities = Country::all();
        foreach($communities as $community){
            $this->comment("Solicitando ".$community->language);
            $this->installComunityUniverses($community);
        }
        $this->comment(count($communities)." new communities added!");

    }

    protected function installComunityUniverses($country){

        $url = 'https://api.ogniter.org/api/community/'.$country->id.'/universes';

        $this->comment("Url ".$url);

        $universeList = json_decode(file_get_contents($url));
        foreach($universeList as $universeJson){
            $this->installUniverse($country, $universeJson);
            $this->comment("Universe updated! (Ogniter ".$universeJson->id.")");
            sleep(2); //Delay, so we don't get timed out
        }
        $this->comment(count($universeList)." new universes added! (Community ".$country->language.")");
    }

    protected function installUniverse($country, $universeJson){

        $url = 'https://api.ogniter.org/api/universe/'.$country->id.'/'.$universeJson->id;

        $ct = json_decode(file_get_contents($url));

        $the_universe = Universe::newIfNotFoundCondition('id', $ct->id);
        $the_universe->id = $ct->id;

        $last_update = time();

        $number = substr($ct->ogame_code, 2);

        $the_universe->language = $country->language;
        $the_universe->ogame_code = $ct->ogame_code;
        $the_universe->domain = $ct->domain;
        $the_universe->country_id = $country->id;
        $the_universe->number = is_numeric($number) ? $number : '';
        $the_universe->name = $ct->local_name;
        $the_universe->timezone = $ct->timezone;
        $the_universe->version = $ct->version;
        $the_universe->speed = $ct->speed;
        $the_universe->speed_fleet = $ct->speed_fleet;
        $the_universe->galaxies = $ct->galaxies;
        $the_universe->systems = $ct->systems;
        $the_universe->extra_fields = $ct->extra_fields;
        $the_universe->acs = $ct->acs;
        $the_universe->rapidfire = $ct->rapidfire;
        $the_universe->def_to_debris = $ct->def_to_debris;
        $the_universe->debris_factor = $ct->debris_factor;
        $the_universe->repair_factor = $ct->repair_factor;
        $the_universe->newbie_protection_limit = $ct->newbie_protection_limit;
        $the_universe->newbie_protection_high = $ct->newbie_protection_high;
        $the_universe->top_score = $ct->highscore;
        $the_universe->donut_galaxy = $ct->donut_galaxy;
        $the_universe->donut_system = $ct->donut_system;
        $the_universe->last_update = $last_update;

        $the_universe->wf_enabled = $ct->wf_enabled;
        $the_universe->wf_minimun_res_lost = $ct->wf_minimun_res_lost;
        $the_universe->wf_minimun_loss_perc = $ct->wf_minimun_loss_perc;
        $the_universe->wf_basic_percentage_repair = $ct->wf_basic_percentage_repair;

        $the_universe->debris_factor_def = $ct->debris_factor_def;
        $the_universe->global_deuterium_save_factor = 0; //Well... will be filled-in later

        $the_universe->api_enabled = $ct->api_enabled;
        $the_universe->api_v6_enabled = $ct->api_v6_enabled;

        $the_universe->active = 1;
        $the_universe->save();

        $is_special = false;
        if($ct->speed > 1 || $ct->speed_fleet > 1 || $ct->debris_factor > 0.3){
            $is_special = true;
        }

        //TODO: get weight by number
        $weight = 1;
        if(is_numeric($number)){
            $weight = 0;
        }

        $meta = UniverseMeta::newIfNotFoundCondition('universe_id', $ct->id);
        $meta->universe_id = $ct->id;

        if(!isset($meta->weight)){
            $meta->local_name = $the_universe->name;
            $meta->show_in_global_stats = 1;
            $meta->weight = $weight;

            $meta->is_special = (int) $is_special;

            $meta->num_players = 0;
            $meta->num_alliances = 0;
            $meta->num_planets = 0;
            $meta->num_moons = 0;
            $meta->normal_players = 0;
            $meta->inactive_players = 0;
            $meta->inactive_30_players = 0;
            $meta->outlaw_players = 0;
            $meta->vacation_players = 0;
            $meta->suspended_players = 0;
            $meta->last_global_update = $ct->last_update;
            $meta->previous_global_update = 0;
        }
        if($meta->local_name==\Config::get('ogame_servers.temporal_name')){
            $meta->local_name = $the_universe->name;
        }
        $meta->save();

        $highscore_sql = Universe::createHighscoreTableSQL($the_universe->language, $ct->id);
        \DB::statement($highscore_sql);

        $updateModel = new Update;

        $update = $updateModel->newIfNotAvailable($ct->id,Update::UPDATE_UNIVERSE);
        $update->last_update = $ct->last_update;
        $update->save();

        $updateModel->initUniverseUpdateRecords($ct->id);

    }

}