<?php

namespace App\Http\Middleware;

use App\Ogniter\Model\Ogame\Country;
use App\Ogniter\Model\Ogame\Universe;
use Closure;

class HandleOgameRequest
{
    protected $countryModel;

    protected $universeModel;

    function __construct(Universe $universeModel, Country $countryModel)
    {
        $this->countryModel = $countryModel;

        $this->universeModel = $universeModel;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $path = explode('/',$request->path());

        $countryCode = $path[0];

        do {
            if(!$countryCode || in_array($countryCode,
                    ['site','terms-of-use','privacy-policy','humans', '_debugbar','games', 'logs-z-secret'])){
                break;
            }
            $country = $this->countryModel->getCountry($countryCode);
            if(!$country){
                \App::abort(404, 'Resource not found');
            }
            Country::setCurrentCountry($country);
            if(empty($path[1])){
                break;
            }
            $universeId = $path[1];
            if(!$universeId || in_array($universeId, ['search','top','country-evolution'])){
                break;
            }
            if(is_numeric($universeId)){
                $universe = $this->universeModel->getUniverseById($universeId);
                Universe::setCurrentUniverse($universe);
            } else {
                $universe = $this->universeModel->select('universes.id','country_id','countries.language AS country_language')
                    ->join('countries','countries.id','=','universes.country_id')
                    ->where('ogame_code', $universeId)->first();
                if($universe){
                    $segment_to_replace = $countryCode."/".$universeId;
                    $segment_to_replace_with = $universe->country_language."/".$universe->id;
                    $redirect = redirect()->to(
                        str_replace($segment_to_replace,$segment_to_replace_with, $_SERVER['REQUEST_URI']),
                        301
                    );
                    $redirect->send();
                }
            }
            do {
                if(!$universe){
                    \App::abort(404, "Resource not found");
                }
                if($universe->active==0){
                    \App::abort(410, "Resource not available");
                }
                if($universe->country_id == $country->id){
                    break;
                }
                
                //handle the migration of the pioneer servers
                $country = $this->countryModel->select('language')->where('id','=',$universe->country_id)->first();
                if(!$country){
                    \App::abort(500, "Missing Community");
                }
                $segment_to_replace = $countryCode."/".$universeId;
                $segment_to_replace_with = $country->language."/".$universe->id;
                $redirect = redirect()->to(
                    str_replace($segment_to_replace,$segment_to_replace_with, $_SERVER['REQUEST_URI']),
                    302
                );
                $redirect->send();
            } while(\FALSE);

        } while(\FALSE);

        return $next($request);
    }
}
