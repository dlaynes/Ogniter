<?php

namespace App\Ogniter\Api\Remote\Ogame\Command;

use Illuminate\Console\Command;

use App\Ogniter\Model\Ogame\Country;

use App\Ogniter\Tools\Timer\TimerBag;

class UpdateCommunities extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ogame:update-communities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the list of active Communities.';

    /**
     * The console command signature
     *
     * @var string
     */
    protected $signature = 'ogame:update-communities';

    public function handle(Country $countryModel)
    {
        $communities = \Config::get('ogame_servers.countries');

        foreach($communities as $community){
            
            $country = $countryModel->newIfNotFoundCondition('language', $community['lang']);
            $country->language = $community['lang'];
            $country->slug = $community['slug'];
            $country->old_domain = $community['old_domain'];
            $country->domain = $community['login'];
            $country->api_domain = $community['domain'];
            $country->flag = $community['flag'];
            $country->save();
        }

        $this->comment("Community list just updated. Count: ".count($communities).PHP_EOL);
    }

}