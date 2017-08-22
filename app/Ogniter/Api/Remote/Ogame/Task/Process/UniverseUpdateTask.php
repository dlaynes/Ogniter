<?php

namespace App\Ogniter\Api\Remote\Ogame\Task\Process;

use App\Ogniter\Model\Ogame\Universe;
use App\Ogniter\Model\Ogame\Country;
use App\Ogniter\Model\Ogame\UniverseMeta;
use App\Ogniter\Api\Remote\Ogame\Task\Request\UniverseRequest;

class UniverseUpdateTask extends ProcessRequestTask
{

    protected $_localName;

    protected $_domain;

    protected $_weight = NULL;

    protected $_isSpecial = FALSE;

    protected $_universeId = NULL;

    protected $_lastUpdate = 0;

    protected $_countryId = NULL;

    function __construct(UniverseRequest $request)
    {
        $this->setRequestObject($request);
    }

    public function setDomain($domain){
        $this->_domain = $domain;
        return $this;
    }

    public function setLocalName($localName){
        $this->_localName = $localName;
        return $this;
    }

    public function setWeight($weight){
        $this->_weight = $weight;
        return $this;
    }

    public function setSpecial($special){
        $this->_isSpecial = !!$special;
        return $this;
    }

    public function setCountryId($countryId){
        $this->_countryId = $countryId;
        return $this;
    }

    public function getUniverseId(){
        return $this->_universeId;
    }

    public function buildUrl(){
        return 'https://'.$this->_domain.'/api/serverData.xml';
    }
    
    public function validateParams()
    {
        if(empty($this->_domain)){
            throw new \Exception("You must define a base domain");
        }
        if($this->_weight===NULL){
            throw new \Exception("You must set a weight");
        }
    }

    public function processTask($ct)
    {
        $ogame_server_id = $ct->ogame_id;
        $language = $ct->language;

        $the_universe = Universe::newIfNotAvailable($language, $ogame_server_id);
        if(!$the_universe){
            throw new \Exception("Universe was not created: s".$ogame_server_id.'.'.$language);
        }

        if(!empty($this->_countryId)){
            //just checking
            $country = Country::where('id', $this->_countryId)->select('id')->first();
        } else {
            $country = Country::where('language', $language)->select('id')->first();
        }

        if(!$country){
            throw new \Exception("Community search failed! Language: ".htmlspecialchars($language));
        }

        $the_universe->domain = $ct->domain;
        $the_universe->country_id = $country->id;
        $the_universe->number = $ct->number;
        $the_universe->name = $ct->name;
        $the_universe->timezone = $ct->timezone;
        $the_universe->version = $ct->version;
        $the_universe->speed = $ct->speed;
        $the_universe->speed_fleet = $ct->speed_fleet;
        $the_universe->galaxies = $ct->galaxies;
        $the_universe->systems = $ct->systems;
        $the_universe->extra_fields = $ct->extra_fields;
        $the_universe->acs = $ct->acs;
        $the_universe->rapidfire = $ct->rapid_fire;
        $the_universe->def_to_debris = $ct->def_to_debris;
        $the_universe->debris_factor = $ct->debris_factor;
        $the_universe->repair_factor = $ct->repair_factor;
        $the_universe->newbie_protection_limit = $ct->newbie_protection_limit;
        $the_universe->newbie_protection_high = $ct->newbie_protection_high;
        $the_universe->top_score = $ct->top_score;
        $the_universe->donut_galaxy = $ct->donut_galaxy;
        $the_universe->donut_system = $ct->donut_system;
        $the_universe->last_update = $ct->last_update;

        $the_universe->wf_enabled = $ct->wf_enabled;
        $the_universe->wf_minimun_res_lost = $ct->wf_minimun_res_lost;
        $the_universe->wf_minimun_loss_perc = $ct->wf_minimun_loss_perc;
        $the_universe->wf_basic_percentage_repair = $ct->wf_basic_percentage_repair;

        $the_universe->debris_factor_def = $ct->debris_factor_def;
        $the_universe->global_deuterium_save_factor = $ct->global_deuterium_save_factor;

        $the_universe->api_enabled = 1;
        $the_universe->api_v6_enabled = 1;

        $the_universe->active = 1;
        $the_universe->save();

        $meta = UniverseMeta::newIfNotFoundCondition('universe_id', $the_universe->id);
        $meta->universe_id = $the_universe->id;

        if(!isset($meta->weight)){
            $meta->local_name = $the_universe->name;
            $meta->show_in_global_stats = 1;
            $meta->weight = $this->_weight;

            $meta->is_special = (int) $this->_isSpecial;

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

        $this->_universeId = $the_universe->id;
        $this->_lastUpdate = $ct->last_update;
    }
    
    public function buildTaskId()
    {
        return 'task-universe-'.$this->_domain;
    }

    public function closeTask()
    {
        if(!empty($this->_updateModel)){
            $this->_updateModel->last_update = (int) $this->_lastUpdate;
        }
        return parent::closeTask(); // TODO: Change the autogenerated stub
    }

}
