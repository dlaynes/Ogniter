<?php

namespace App\Ogniter\Maintenance\Command\Restore;

use Illuminate\Console\Command;

use App\Ogniter\Model\Ogame\Country;

class LoadCommunities extends Command {

	public $url = 'https://api.ogniter.org/api/community';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ogniter:load-communities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restores previously created communities';

    public function handle()
    {
        $time_t = time();

        //...
        $communities = json_decode(file_get_contents($this->url));

        foreach($communities as $community){
        	$country = new Country();
        	$country->id = $community->id;
        	$country->language = $community->language;
        	$country->flag = $community->flag;
        	$country->domain = $community->domain;
        	$country->api_domain = $country->old_domain = '';
        	$country->slug = $community->slug;
        	$country->available = 1;
        	$country->save();

        }

        $this->comment(count($communities)." new communities added!");

    }

}