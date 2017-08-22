<?php

namespace App\Providers;

use App\Ogniter\Model\Ogame\Country;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;

class ClassicViewServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @param Request $request
     *
     * @param Country $countryModel
     *
     * @return void
     */
    public function boot()
    {
        \View::composers([
            '\App\Http\ViewComposers\Classic\DefaultComposer'  => [
                'errors.404',
                'errors.410'
            ]
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}