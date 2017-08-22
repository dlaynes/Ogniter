<?php

namespace App\Ogniter\Maintenance\Command\Websites;

use App\Ogniter\Model\Website\OgameWebsite;
use Illuminate\Console\Command;
use DB;

class AddNewWebsite extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ogame:website-add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds a new website to the list of available Ogame websites';

    protected $signature = 'ogame:website-add {name} {description} {url} {asset_image?}';

    public function handle(OgameWebsite $websiteModel)
    {
        $websiteModel->name = $this->argument('name');
        $websiteModel->description = $this->argument('description');
        $websiteModel->review = NULL; //...
        $websiteModel->url = $this->argument('url');

        $image = $this->argument('asset_image');
        $websiteModel->image = $image ? basename($image) : '';
        $websiteModel->votes = 0;
        $websiteModel->score = 0;
        $websiteModel->save();
        $this->info("New website added succesfully");
    }
}