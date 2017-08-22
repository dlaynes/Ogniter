<?php

namespace App\Http\Controllers\Classic\Server;

use App\Http\Controllers\Controller;
use App\Ogniter\Model\Ogame\BannedUsers;
use App\Ogniter\Model\Ogame\Country;
use App\Ogniter\Model\Ogame\Universe;
use App\Ogniter\Model\Ogame\UniverseHistory;
use App\Ogniter\Model\Ogame\Update;
use App\Ogniter\ViewHelpers\Tags;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class PrangerController extends Controller
{

    protected $country;

    protected $universe;

    function __construct()
    {
        $this->country = Country::getCurrentCountry();

        $this->universe = Universe::getCurrentUniverse();

        \View::composers([
            '\App\Http\ViewComposers\Classic\DefaultComposer' => [
                'classic.pages.servers.banned_users'
            ]
        ]);

        \View::composers([
            '\App\Http\ViewComposers\Classic\UniverseComposer' => [
                'classic.partials.servers.nav'
            ]
        ]);

        \View::share([
            'uniShortCode' => $this->country->language . '/' . $this->universe->id,
            'currentCountry' => $this->country,
            'currentUniverse' => $this->universe
        ]);
    }

    function index(Request $request, BannedUsers $bannedUsersModel, Update $updateModel, Tags $tagsHelper, $countryLang, $universeId)
    {
        $perPage = 50;

        $currentPage = $request->get('page', 1);
        if($currentPage<1){ $currentPage=1;}
        $offset = ($currentPage - 1 ) * $perPage;

        $lang = trans();
        $tagsHelper->generateLanguageSettings($lang);

        $last = $updateModel->newIfNotAvailable($this->universe->id, Update::UPDATE_PLAYER);

        $users = $bannedUsersModel->getList($this->universe->id, $perPage, $offset);
        $user_count = $bannedUsersModel->count($this->universe->id);

        $path = $this->country->language.'/'.$this->universe->id.'/banned_users';

        $pager = new LengthAwarePaginator($users, $user_count, $perPage, $currentPage);
        $pager
            ->setPath(url($path));

        $data = [
            'pager' => $pager,
            'tagsHelper' => $tagsHelper,
            'last_update' => $last['last_update'],
            'result_count' => $user_count,
            'results' => $users,
            'PAGE_TITLE' => 'Ogniter - Banned Users: '.$this->universe->local_name.' ('.$this->country->domain.')',
            'PAGE_DESCRIPTION' => 'Banned user list in '.$this->universe->local_name.' ('.$this->country->domain.')'
        ];
        return view('classic.pages.servers.banned_users', $data);
    }


}