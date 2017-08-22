<?php

namespace App\Http\Controllers\Classic\Site;

use App\Http\Controllers\Controller;
use App\Ogniter\Model\Ogame\UniverseHistory;

class IndexController extends Controller {

    function __construct(){

        \View::composers([
            '\App\Http\ViewComposers\Classic\DefaultComposer'  => [
                'classic.pages.site.index',
                'classic.pages.site.faq',
                'classic.pages.site.terms_of_use',
                'classic_pages.site_privacy_policy',
                'classic.pages.site.humans'
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

    function index(){
        return view('classic.pages.site.index');
    }

    function faq(){
        return view('classic.pages.site.faq');
    }

    function humans(){
        return view('classic.pages.site.humans');
    }

    function terms_of_use(){
        return view('classic.pages.site.terms_of_use');
    }

    function privacy_policy(){
        return view('classic.pages.site.privacy_policy');
    }

}