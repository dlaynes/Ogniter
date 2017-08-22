<?php

namespace App\Http\Controllers\Classic\Site;

use App\Http\Controllers\Controller;
use App\Ogniter\Model\Ogame\UniverseHistory;
use App\Ogniter\Model\Website\OgameWebsite;
use Illuminate\Http\Request;

class WebsiteController extends Controller {

    function __construct(){

        \View::composers([
            '\App\Http\ViewComposers\Classic\DefaultComposer'  => [
                'classic.pages.site.websites',
                'classic.pages.site.website'
            ]
        ]);

        \View::composers([
            '\App\Http\ViewComposers\Classic\WorldStatisticsComposer'  => [
                'classic.partials.shared.statistics'
            ]
        ]);

        \View::composers([
            '\App\Http\ViewComposers\Classic\PollComposer'  => [
                'classic.partials.shared.poll_form'
            ]
        ]);

    }

    function index(OgameWebsite $websiteModel, UniverseHistory $statisticsHistoryModel, $order_by='votes'){
        //TODO: cambiar por el poll?
        $data = [
            'sites' => $websiteModel->getSites($order_by),
            'worldStatistics' => $statisticsHistoryModel->getStats($country_id=0,$universe_id=0)
        ];

        return view('classic.pages.site.websites', $data);
    }

    function detail(OgameWebsite $websiteModel, UniverseHistory $statisticsHistoryModel, $id=0){
        $website = $websiteModel->where('id','=',$id)->first();
        if(!$website){
            \App::abort(404, 'Resource not found');
        }
        $data = [
            'site' => $website,
            'worldStatistics' => $statisticsHistoryModel->getStats($country_id=0,$universe_id=0)
        ];
        return view('classic.pages.site.website', $data);
    }

    function vote(Request $request, OgameWebsite $websiteModel, $id=0){
        $score = $request->get('score');
        if($score < 1 || $score > 5){
            return 0;
        }
        $session = $request->session();
        if($session->has('vote_'.$id)){
            return 0;
        }

        $site = $websiteModel->where('id','=',$id)->first();
        if(!$site){
            return 0;
        }

        $site->votes++;
        $site->score = $site['score'] + $score;
        $site->save();

        //We store the data for who knows how long
        $session->set('vote_'.$id, 1);
        return 1;
    }

}