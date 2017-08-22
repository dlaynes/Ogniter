<?php

namespace App\Ogniter\Api\Remote\Ogame\Command;

use Illuminate\Console\Command;

use App\Ogniter\Model\Ogame\Country;
use App\Ogniter\Model\Ogame\Universe;

use App\Ogniter\Api\Remote\Ogame\Task\Process\UniverseQueueUpdateTask;
use App\Ogniter\Api\Remote\Ogame\Task\Request\UniverseQueueRequest;

use App\Ogniter\Tools\Timer\TimerBag;

class UpdateApiUniverses extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ogame:update-api-universes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the list of active APIs in the Ogame Universes. Adds new ones to the install queue list';

    /**
     * The console command signature
     *
     * @var string
     */
    protected $signature = 'ogame:update-api-universes {remote_url?} {country_id?}';

    public function handle(TimerBag $timer)
    {
        $timer->addTask('load-new-universes');

        $extra_universes = [];

        $remote_url = $this->argument('remote_url');
        if(empty($remote_url)){
            $communities = Country::select('id','domain')
                ->where('available','=',1)->get();
        } else {
            /*
             * If you add a valid API remote url, we will try to load the active list of universes from it,
             * only if the other universes of the country are disabled or down.
             * Also, this restricts the search to only one community/country
             * */

            $parts = Universe::extractParts($remote_url);
            if(empty($parts['language']) || empty($parts['number'])){
                $this->error("Invalid format");
                return;
            }

            /*
             * Detecting the country automatically doesn't work for the Pioneer universes,
             * since their base domain is the same as the uk domain
             * Remember to append the country id at the end of the command text when adding those.
             * If the pioneers community was created before the uk community, add the country_id for both
             * */

            $country_id = $this->argument('country_id');
            if($country_id) {
                $cm = Country::select('id','domain', 'api_domain')
                    ->where('id','=',$country_id)
                    ->where('available','=',1)->first();
            } else {
                $cm = Country::select('id','domain', 'api_domain')
                    ->where('api_domain','=', $parts['api_base'])
                    ->where('available','=',1)->first();
            }
            if(!$cm){
                $this->error("Community ".htmlspecialchars($parts['api_base'])." (".$country_id.")"." not found (or deactivated)");
                return;
            }
            //dd($cm);

            $communities = [$cm];
            //We trust input data for now
            $extra_universes[] = (object) [
                'id' => NULL,
                'language' => $parts['language'],
                'number' => (int) $parts['number'],
                'domain' => $parts['domain']
            ];
        }

        foreach($communities as $community){

            $invalid_universes = array();
            $valid_universes = array();
            $new_universes = array();

            $current_active_universes = Universe::select('id','language','number','domain')
                ->where('active','=',1)
                ->where('api_enabled','=', 1)
                ->where('country_id','=',$community->id)->get();

            if(count($extra_universes)){
                $current_active_universes->push($extra_universes[0]);
            }

            if(!count($current_active_universes)){
                continue;
            }

            $pos = 0;
            $found = FALSE;
            //Asumptions:
            //- there is at least one active universe in the chosen country in the database
            //- the server is active in the moment the request is sent
            //You should edit/remove the servers directly if Gameforge removed a Country/Community,
            //how?, just run ogame:toggle-universe {universe_id} to deactivate it
            //If you have an active API url from a country, import it using the optional parameter
            //and it will restore the real active universes from said country
            while(!$found){
                try {
                    if(!isset($current_active_universes[$pos])){
                        $this->comment("Error, reached end of list in ".$community->domain);
                        break;
                    }

                    $universe = $current_active_universes[$pos];
                    $domain = Universe::formatDomain($universe->language, $universe->number);
                    //???
                    $domain = str_replace('yu.ogame.gameforge.com','ba.ogame.gameforge.com', $domain);

                    $this->comment('Looking for '.$domain);

                    $task = new UniverseQueueUpdateTask(new UniverseQueueRequest());
                    $task->setDomain($domain)
                        ->setCountryId($community->id)
                        ->run();

                    $valid_universes = $task->getValidUniverses();
                    $new_universes = $task->getNewUniverses();

                    $found = TRUE;
                } catch(\Exception $e){
                    $this->comment($e->getMessage());
                    //Do nothin', probable Exodus Universe
                }
                $pos++;
            }

            foreach($new_universes as $new_universe){
                $this->comment("New universe found! ".$new_universe);
            }

            //$this->comment("Active universes in remote list: ".implode(", ", array_keys($valid_universes)) );

            foreach($current_active_universes as $cau){
                if(!array_key_exists($cau->domain, $valid_universes)){
                    $invalid_universes[] = $cau->id;
                }
            }

            if(count($invalid_universes)){
                $this->comment("Exodus universes found: ".implode(',', $invalid_universes)." in ".$community->domain);
                //Laziness
                foreach($invalid_universes as $invalid_universe){
                    $invalid_universe = (int) $invalid_universe;
                    \DB::statement("UPDATE universes SET api_enabled=0, api_v6_enabled=0 WHERE id=".$invalid_universe);
                }
            }
        }
        $timer->stopTask('load-new-universes');
        $item = $timer->getItem('load-new-universes');

        $this->comment("Registration of new universes just ended. It took ".$item->getDifference()."s".PHP_EOL);

    }

}