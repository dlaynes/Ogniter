<?php

namespace App\Http\Controllers\Classic\Server;

use App\Http\Controllers\Controller;
use App\Ogniter\Model\Ogame\Country;
use App\Ogniter\Model\Ogame\Highscore;
use App\Ogniter\Model\Ogame\HighscoreLog;
use App\Ogniter\Model\Ogame\Planet;
use App\Ogniter\Model\Ogame\Player;
use App\Ogniter\Model\Ogame\PlayerChanges;
use App\Ogniter\Model\Ogame\PlayerMeta;
use App\Ogniter\Model\Ogame\Universe;
use App\Ogniter\Model\Ogame\UniverseHistory;
use App\Ogniter\ViewHelpers\Tags;

class PlayerController extends Controller {


    protected $country;

    protected $universe;

    function __construct()
    {
        $this->country = Country::getCurrentCountry();

        $this->universe = Universe::getCurrentUniverse();

        \View::composers([
            '\App\Http\ViewComposers\Classic\DefaultComposer'  => [
                'classic.pages.servers.player',
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

    function index(Player $playerModel,
        PlayerChanges $playerChangesModel,
        PlayerMeta $playerMetaModel,
        Planet $planetModel,
        Highscore $highscoreModel,
        UniverseHistory $statisticsHistoryModel,
        Tags $tagsHelper,
                   $countryLanguage='', $universeId='', $player_id=0){

        $universe_id = $this->universe->id;
        $language = $this->universe->language;
        $player_id = (int) $player_id;

        $data = array();
        $data['player'] = $playerModel->getFullInfo($universe_id, $player_id);
        if(!$data['player']){
            return \View::make('classic.pages.servers.not_found');
        }

        $data['player']->name = \str_replace('Pedobear', 'P***bear', $data['player']->name);

        $lang = trans();
        $a = array();
        $a['PAGE_TITLE'] = $lang->trans('ogniter.title_server_player');
        $a['PAGE_DESCRIPTION'] = $lang->trans('ogniter.description_server_player');

        $a = str_replace(array('%s%','%server%','%domain%'),
            array($data['player']->name, $this->universe->local_name, $this->country->domain), $a);

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

        $data['rankings'] = [];

        if($this->universe->api_enabled){
            $from = time();
        } else {
            $from = HighscoreLog::getLatestRankingUpdate($universe_id, 0, 0);
        }

        //2016 and we are still doing this
        for($i=0; $i<8; $i++){
            $data['rankings'][$i] = $highscoreModel->getResultsFrom($language, $universe_id, $player_id, 1, $i, $from);
        }

        $data['planets'] = $planetModel->getFromPlayer($universe_id, $player_id);

        $data['time'] = time();

        $default_values = array(
            'name' => NULL,
            'alliance_id' => NULL,
            'alliance_name' => NULL,
            'status' => NULL,
        );
        $changes = array();

        $player_changes = $playerChangesModel->getChangesFrom($universe_id, $player_id);
        foreach($player_changes as $change){
            if(!empty($default_values['name'])){
                if($default_values['name']!=$change->name){
                    $changes[] = array(
                        'change' => $lang->trans('ogniter.og_name'),
                        'from' => $default_values['name'],
                        'to' => $change->name,
                        'date' => date('Y-m-d', $change->modified_on)
                    );
                    $default_values['name']=$change->name;
                }
                if($default_values['alliance_id']!=$change->alliance_id){
                    $changes[] = array(
                        'change' => $lang->trans('ogniter.og_alliance'),
                        'from' => $default_values['alliance_name'],
                        'to' => $change->alliance_name,
                        'date' => date('Y-m-d', $change->modified_on)
                    );
                    $default_values['alliance_id']=$change->alliance_id;
                    $default_values['alliance_name'] = $change->alliance_name;
                }
                if($default_values['status']!=$change->status){
                    $status_string_before = Player::numberToStatus($default_values['status']);
                    $status_string_after = Player::numberToStatus($change->status);

                    $changes[] = array(
                        'change' =>  $lang->trans('ogniter.player_status'),
                        'from' => $status_string_before != '' ? $status_string_before : 'n',
                        'to' => $status_string_after != '' ? $status_string_after : 'n',
                        'date' => date('Y-m-d', $change->modified_on)
                    );
                    $default_values['status']=$change->status;
                }
            } else {
                $default_values['name'] = $change->name;
                $default_values['alliance_id'] = $change->alliance_id;
                $default_values['alliance_name'] = $change->alliance_name;
                $default_values['status'] = $change->status;
            }
        }

        $data['changes'] = $changes;

        $data['player']->views = $playerMetaModel->getVisits($universe_id,$player_id);
        $data['player']->views++;

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

        $playerMetaModel->addVisit($universe_id,$player_id);

        $data['show_score'] = (strpos($data['player']->status, 'a') ===FALSE && count($data['rankings']));

        return \View::make("classic.pages.servers.player", $data);
    }
}