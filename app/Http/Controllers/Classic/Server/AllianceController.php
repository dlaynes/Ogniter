<?php

namespace App\Http\Controllers\Classic\Server;

use App\Http\Controllers\Controller;
use App\Ogniter\Model\Ogame\Alliance;
use App\Ogniter\Model\Ogame\AllianceChanges;
use App\Ogniter\Model\Ogame\AllianceMeta;
use App\Ogniter\Model\Ogame\Country;
use App\Ogniter\Model\Ogame\Highscore;
use App\Ogniter\Model\Ogame\HighscoreLog;
use App\Ogniter\Model\Ogame\Player;
use App\Ogniter\Model\Ogame\Universe;
use App\Ogniter\Model\Ogame\UniverseHistory;
use App\Ogniter\ViewHelpers\Tags;

class AllianceController extends Controller {


    protected $country;

    protected $universe;

    function __construct()
    {
        $this->country = Country::getCurrentCountry();

        $this->universe = Universe::getCurrentUniverse();

        \View::composers([
            '\App\Http\ViewComposers\Classic\DefaultComposer'  => [
                'classic.pages.servers.alliance',
                'classic.pages.servers.not_found'
            ]
        ]);

        \View::composers([
            '\App\Http\ViewComposers\Classic\UniverseComposer'  => [
                'classic.partials.servers.nav'
            ]
        ]);

        \View::share([
            'uniShortCode' => $this->country->language.'/'.$this->universe->id,
            'currentCountry' => $this->country,
            'currentUniverse' => $this->universe
        ]);

    }
    
    function index(
        Alliance $allianceModel,
        Player $playerModel,
        Highscore $highscoreModel,
        AllianceChanges $allianceChangesModel,
        UniverseHistory $statisticsHistoryModel,
        AllianceMeta $allianceMetaModel,
        Tags $tagsHelper,
        $countryLanguage='', $universeId='', $alliance_id=0){

        //I don't trust the unused parameters for some reason
        $universe_id = $this->universe->id;
        $language = $this->universe->language; //Do not confuse with the country slug
        $alliance_id = (int) $alliance_id;

        $data = [];
        $data['alliance'] = $allianceModel->getFullInfo($universe_id, $alliance_id);
        if(!$data['alliance']){
            return \View::make('classic.pages.servers.not_found');
        }

        $lang = trans();

        $a = array();
        $a['PAGE_TITLE'] = $lang->trans('ogniter.title_server_alliance');
        $a['PAGE_DESCRIPTION'] = $lang->trans('ogniter.description_server_alliance');

        $a = str_replace(array('%s%','%server%','%domain%'),
                array($data['alliance']->name, $this->universe->local_name, $this->country->domain), $a);

        $data['PAGE_TITLE'] = $a['PAGE_TITLE'];
        $data['PAGE_DESCRIPTION'] = $a['PAGE_DESCRIPTION'];
        $data['time'] = time();
        $data['dtypes'] = [
            $lang->trans('ogniter.og_total'),
            $lang->trans('ogniter.og_economy'),
            $lang->trans('ogniter.og_research'),
            $lang->trans('ogniter.og_mil_points'),
            $lang->trans('ogniter.og_lost_mil_points'),
            $lang->trans('ogniter.og_built_mil_points'),
            $lang->trans('ogniter.og_destroyed_mil_points'),
            $lang->trans('ogniter.og_honor')
        ];

        $stats = $statisticsHistoryModel->getStats(0, $universe_id);
        $player_count = $stats->num_players;
        $data['players'] = $playerModel->getFromAlliance($universe_id, $alliance_id);

        $data['rankings'] = [];

        if($this->universe->api_enabled){
            $from = time();
        } else {
            $from = HighscoreLog::getLatestRankingUpdate($universe_id, 0, 0);
        }

        //2016 and we are still doing this
        for($i=0; $i<8; $i++){
            $data['rankings'][$i] = $highscoreModel->getResultsFrom($language, $universe_id, $alliance_id, 2, $i, $from);
        }

        $default_values = array(
            'name' => NULL,
            'tag' => NULL,
            'open' => NULL
        );
        $changes = array();
        $alliance_changes = $allianceChangesModel->getChangesFrom($universe_id,$alliance_id);

        //TODO: tracker de tiempo
        foreach($alliance_changes as $change){
            if(!empty($default_values['name'])){
                if($default_values['name']!=$change->name){
                    $changes[] = array(
                        'change' =>  $lang->trans('ogniter.og_name'),
                        'from' => $default_values['name'],
                        'to' => $change->name,
                        'date' => date('Y-m-d', $change->modified_on)
                    );
                    $default_values['name']=$change->name;
                }
                if($default_values['tag']!=$change->tag){
                    $changes[] = array(
                        'change' =>  $lang->trans('ogniter.og_alliance_tag'),
                        'from' => $default_values['tag'],
                        'to' => $change->tag,
                        'date' => date('Y-m-d', $change->modified_on)
                    );
                    $default_values['tag']=$change->tag;
                }
                if($default_values['open']!=$change->open){
                    $changes[] = array(
                        'change' =>  $lang->trans('ogniter.og_alliance_registration'),
                        'from' => $default_values['open'] ? $lang->trans('ogniter.og_enabled') : $lang->trans('ogniter.og_disabled'),
                        'to' => $change->open ? $lang->trans('og_enabled') : $lang->trans('ogniter.og_disabled'),
                        'date' => date('Y-m-d', $change->modified_on)
                    );
                    $default_values['open']=$change->open;
                }
            } else {
                $default_values['name'] = $change->name;
                $default_values['tag'] = $change->tag;
                $default_values['open'] = $change->open;
            }
        }

        $data['changes'] = $changes;
        
        $data['alliance']->views = $allianceMetaModel->getVisits($universe_id,$alliance_id);
        $data['alliance']->views++;

        $tagsHelper->generateLanguageSettings($lang);
        $data['tagsHelper'] = $tagsHelper;

        if($player_count > 251){
            $data['low_250'] = $player_count - 251;
        } else{
            $data['low_250'] = 0;
        }
        if($player_count > 101){
            $data['low_100'] = $player_count - 101;
        } else{
            $data['low_100'] = 0;
        }
        if($player_count > 11){
            $data['low_10'] = $player_count - 11;
        } else{
            $data['low_10'] = 0;
        }

        $data['weekly_dif'] = 0;
        $data['monthly_dif'] = 0;
        $data['weekly_score'] = 0;
        $data['monthly_score'] = 0;

        $allianceMetaModel->addVisit($universe_id,$alliance_id);

        return \View::make('classic.pages.servers.alliance', $data);
    }


}