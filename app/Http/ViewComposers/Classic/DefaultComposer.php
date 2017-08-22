<?php

namespace App\Http\ViewComposers\Classic;

use App\Ogniter\Model\Ogame\Universe;
use Illuminate\Http\Request;
use App\Ogniter\Model\Ogame\Country;
use App\Ogniter\Model\Website\Language;
use App\Ogniter\Model\Website\Theme;

use Jenssegers\Agent\Agent;

class DefaultComposer {

    protected $request;

    protected $languageModel;

    protected $countryModel;

    protected $themeModel;

    public function __construct(Request $request, Language $languageModel,
                                Country $countryModel, Theme $themeModel)
    {
        $this->request = $request;
        $this->languageModel = $languageModel;
        $this->countryModel = $countryModel;
        $this->themeModel = $themeModel;
        // Dependencies automatically resolved by service container...
        //$this->users = $users;
    }

    /**
     * Bind data to the view.
     *
     * @return void
     */

    public function compose( $view)
    {
        \View::composers([
            '\App\Http\ViewComposers\DefaultComposer'  => [
                'errors.404',
                'errors.410'
            ]
        ]);

        $baseDomain = env('APP_URL');
        $currentLanguageId = \App::getLocale();

        $fullUrl = $this->request->fullUrl();
        $url = parse_url($fullUrl);

        $currentPath = str_replace($this->request->root(), '', $fullUrl);
        $baseScheme = $url['scheme'];

        $baseUrl = $baseScheme.'://'.$currentLanguageId.'.'.$baseDomain;

        $currentThemeId = 'cyborg';
        try {
            $session = $this->request->session();
            $ct = $session->get('currentTheme');
            
            if($ct && $this->themeModel->existsByKey($ct)){
                $currentThemeId = $ct;
            }
        } catch(\Exception $e){

        }

        $languages = $this->languageModel->getRecords();

        $countries = $this->countryModel->getList();
        $themes = $this->themeModel->getRecords();

        $universes = \NULL;
        $country = Country::getCurrentCountry();
        if($country){
            $universes = Universe::getUniversesFrom($country->language);
        }

        $lang = trans();
        \View::share(
            [
                'environment' => env('APP_ENV', 'production'),
                'agent' => new Agent(),
                'currentLanguageId' => $currentLanguageId,
                'currentThemeId' => $currentThemeId,
                'baseScheme' => $baseScheme,
                'baseDomain' => $baseDomain,
                'baseUrl' => $baseUrl,
                'cdnHost' => $baseScheme.'://'.$baseDomain.'/cdn/',
                'lang'=> $lang,
                'themes' => $themes,
                'languages' => $languages,
                'currentPath' => $currentPath,
                'countries' => $countries,
                'request' => $this->request,
                'universes' => $universes
            ]
        );

        //$view->with('count', $this->users->count());
    }
}