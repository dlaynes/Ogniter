<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', '\App\Http\Controllers\Classic\Site\IndexController@index');

Route::get('logs-z-secret', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

Route::get('/privacy-policy', '\App\Http\Controllers\Classic\Site\IndexController@privacy_policy');
Route::get('/terms-of-use', '\App\Http\Controllers\Classic\Site\IndexController@terms_of_use');
Route::get('/humans', '\App\Http\Controllers\Classic\Site\IndexController@humans');

Route::group(['prefix'=>'site'], function(){
    Route::get('/faq', '\App\Http\Controllers\Classic\Site\IndexController@faq');
    Route::get('/recommended/{order_by?}', '\App\Http\Controllers\Classic\Site\WebsiteController@index');
    Route::get('/detail/{id}', '\App\Http\Controllers\Classic\Site\WebsiteController@detail');
    Route::post('vote/website/{id}', '\App\Http\Controllers\Classic\Site\WebsiteController@vote');
    Route::get('/polls/{pag?}','\App\Http\Controllers\Classic\Site\PollController@index');
    Route::get('/poll/{id}','\App\Http\Controllers\Classic\Site\PollController@detail');
    Route::post('/poll/{id}','\App\Http\Controllers\Classic\Site\PollController@vote');
    Route::get('/evolution','\App\Http\Controllers\Classic\Site\EvolutionController@index');
    Route::get('/top/{category_type}/{type?}/{mode?}', '\App\Http\Controllers\Classic\Site\TopController@top');
    Route::get('/theme/{theme}/{uri}', '\App\Http\Controllers\Classic\Site\ThemeController@changeTheme');
    Route::get('/flight_times', '\App\Http\Controllers\Classic\Site\ToolsController@flightSimulator');
});

Route::group(['prefix'=>'games'], function(){
   Route::get('/bon-voyage','\App\Http\Controllers\Classic\Games\IndexController@bonVoyage');
});

Route::group(['prefix'=>'{country}'], function(){
    Route::get('/', '\App\Http\Controllers\Classic\Domain\IndexController@index');
    Route::get('/top/{category_type}/{type?}/{mode?}', '\App\Http\Controllers\Classic\Domain\TopController@top');
    Route::get('/country-evolution', '\App\Http\Controllers\Classic\Domain\EvolutionController@index');
    Route::get('/search', '\App\Http\Controllers\Classic\Domain\SearchController@index');
    Route::post('/search', '\App\Http\Controllers\Classic\Domain\SearchController@doSearch');
});

Route::group(['prefix'=>'{country}/{universe}'], function(){
    Route::get('/', '\App\Http\Controllers\Classic\Server\IndexController@index');

    Route::get('/galaxy/{gal?}/{pos?}', '\App\Http\Controllers\Classic\Server\GalaxyController@index');
    Route::post('/galaxy', '\App\Http\Controllers\Classic\Server\GalaxyController@doSearch');
    Route::get('/ajax_planet/{id}', '\App\Http\Controllers\Classic\Server\GalaxyController@ajaxPlanet');

    Route::get('/player/{id?}', '\App\Http\Controllers\Classic\Server\PlayerController@index');
    Route::get('/alliance/{id?}', '\App\Http\Controllers\Classic\Server\AllianceController@index');

    Route::get('/search-form/{search_string?}', '\App\Http\Controllers\Classic\Server\SearchController@form');
    Route::post('/search', '\App\Http\Controllers\Classic\Server\SearchController@doSearch');
    //deprecated
    Route::get('/search/{search_string?}/{offset?}', '\App\Http\Controllers\Classic\Server\SearchController@index');

    Route::get('/highscore/{category_type?}/{type?}', '\App\Http\Controllers\Classic\Server\RankingController@highscore');
    //deprecated
    Route::get('/ranking/{category?}/{type?}/{order_by?}/{order?}/{pag?}',
        '\App\Http\Controllers\Classic\Server\RankingController@index');

    Route::get('/track/{track_type}/{gal?}/{param?}/{planet_type?}',
        '\App\Http\Controllers\Classic\Server\TrackController@index');
    Route::post('/track/{track_type}/{gal?}/{param?}/{planet_type?}',
        '\App\Http\Controllers\Classic\Server\TrackController@doTrack');

    Route::get('/top_flop/{order?}/{category?}/{type?}',
        '\App\Http\Controllers\Classic\Server\TopFlopController@index');

    Route::get('/statistics/{statistics_type}/{type}/{period}/{entities}',
        '\App\Http\Controllers\Classic\Server\StatisticsController@index');
    Route::post('/statistics/{statistics_type}/{type}/{period}/{entities}',
        '\App\Http\Controllers\Classic\Server\StatisticsController@doLimit');

    Route::get('/evolution', '\App\Http\Controllers\Classic\Server\EvolutionController@index');

    Route::get('/banned_users/{pag?}', '\App\Http\Controllers\Classic\Server\PrangerController@index');

    Route::get('/comparison', '\App\Http\Controllers\Classic\Server\ComparisonController@index');
    Route::post('/autocomplete_tags/{category}', '\App\Http\Controllers\Classic\Server\ComparisonController@ajaxSearch');
    Route::post('/comparison', '\App\Http\Controllers\Classic\Server\ComparisonController@doComparison');

    Route::get('/flight_times/{from?}/{to?}',
        '\App\Http\Controllers\Classic\Server\ToolsController@flightSimulator');
});

