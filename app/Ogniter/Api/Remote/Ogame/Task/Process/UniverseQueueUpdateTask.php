<?php

namespace App\Ogniter\Api\Remote\Ogame\Task\Process;

use App\Ogniter\Api\Remote\Ogame\Task\Request\UniverseQueueRequest;
use App\Ogniter\Model\Ogame\Universe;
use App\Ogniter\Model\Ogame\UniverseQueue;

class UniverseQueueUpdateTask extends ProcessRequestTask
{

    protected $_newUniverses = [];

    protected $_validUniverses = [];

    protected $_domain = '';

    protected $_countryId = 0;

    function __construct(UniverseQueueRequest $request)
    {
        $this->setRequestObject($request);
    }

    public function getValidUniverses(){
        return $this->_validUniverses;
    }

    public function getNewUniverses(){
        return $this->_newUniverses;
    }

    public function setDomain($domain){
        $this->_domain = $domain;
        return $this;
    }

    public function setCountryId($countryId){
        $this->_countryId = $countryId;
        return $this;
    }

    public function buildTaskId()
    {
        return 'active-universes-from-'.$this->_domain;
    }

    public function processTask($universes)
    {
        $private_servers = \Config::get('ogame_servers.private_servers');

        foreach($universes['universes'] as $test_universe){
            $number = $test_universe['number'];
            //We currently do not have access to those, unless you configure the Auth settings
            if(in_array($number, $private_servers)){
                continue;
            }
            $domain = str_replace(array('http://','https://'),array('',''),$test_universe['url']);

            //Universe allowed to be inserted, or marked as active
            $this->_validUniverses[$domain] = 1;

            //Don't add universes which already exist
            if(Universe::where('domain','=',$domain)->count()){
                $universe = Universe::select('id','language','active','api_enabled','api_v6_enabled')
                    ->where('domain','=', $domain)->first();

                //The misterious phoenix universe
                if(!$universe->active){
                    $universe->active = 1;
                    $universe->save();

                    //Add the highscore table if missing
                    $sql = Universe::createHighscoreTableSQL($universe->language, $universe->id);
                    \DB::statement($sql);
                }
                if(!$universe->api_enabled){
                    $universe->api_enabled=1;
                    $universe->api_v6_enabled=1;
                    $universe->save();
                }
                continue;
            }
            if(UniverseQueue::where('domain','=',$domain)->count()){
                continue;
            }
            UniverseQueue::insert([
                'country_id' => $this->_countryId,
                'domain' => $domain,
                'processed' => 0
            ]);
            $this->_newUniverses[] = $domain;
        }
    }

    public function validateParams()
    {
        if(empty($this->_domain)){
            throw new \Exception("Universe Queue - You must define a base domain");
        }
        if(empty($this->_countryId)){
            throw new \Exception("Universe Queue - You must define a base country ID");
        }
    }

    public function buildUrl()
    {
        return 'https://' . $this->_domain . '/api/universes.xml';
    }

}