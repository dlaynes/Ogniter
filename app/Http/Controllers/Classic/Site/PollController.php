<?php

namespace App\Http\Controllers\Classic\Site;

use App\Http\Controllers\Controller;
use App\Ogniter\Model\Ogame\UniverseHistory;
use App\Ogniter\Model\Website\Poll;
use Illuminate\Http\Request;

class PollController extends Controller {

    function __construct(){

        \View::composers([
            '\App\Http\ViewComposers\Classic\DefaultComposer'  => [
                'classic.pages.site.polls',
                'classic.pages.site.poll'
            ]
        ]);

        \View::composers([
            '\App\Http\ViewComposers\Classic\WorldStatisticsComposer'  => [
                'classic.partials.shared.statistics'
            ]
        ]);

    }

    function index(Poll $pollModel, UniverseHistory $statisticsHistoryModel){
        //TODO: pagination
        $data = [
            'polls' => $pollModel->orderBy('id','DESC')->get(),
            'worldStatistics' => $statisticsHistoryModel->getStats($country_id=0,$universe_id=0)
        ];

        return view('classic.pages.site.polls', $data);
    }

    function detail(Poll $pollModel, $id=0){
        $poll = $pollModel->getResults($id);
        if(!$poll){
            \App::abort(404, 'Poll not found');
        }

        $data = [
            'poll' => $poll
        ];
        return view('classic.pages.site.poll', $data);
    }

    function vote(Request $request, Poll $pollModel, $id=0){
        $answer = $request->get('answer');
        $session = $request->session();
        $session_verify = 'poll_'.$id;
        do {
            if($session->has($session_verify)) break;

            if(!$pollModel->where('id','=', $id)->count()){
                \App::abort(404, 'Poll not found');
            }

            $pollModel->addVote($id, $answer);

            $session->set($session_verify, 1);

        } while(\FALSE);

        return redirect('/site/poll/'.$id);
    }

}