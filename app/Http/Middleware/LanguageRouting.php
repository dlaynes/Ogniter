<?php

namespace App\Http\Middleware;

use App\Ogniter\Model\Website\Language;
use Closure;

class LanguageRouting
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $languageModel = new Language();

        $user_lang = $request->header('Accept-Language');

        $fullUrl = $request->fullUrl();

        $url = parse_url($fullUrl);

        $lang = substr($url['host'],0,strpos($url['host'],'.'));
        $request_domain = substr($url['host'],strpos($url['host'],'.')+1);

        $currentPath = str_replace($request->root(), '', $fullUrl);
        $baseDomain = env('APP_URL');
        
        $languages = $languageModel->getRecords();
        if(!array_key_exists($lang, $languages)){
            $lang = $languageModel->getDefaultLanguageCode();
            redirect($url['scheme'].'://'.$lang.'.'.$baseDomain.$currentPath)->send();
        }
        
        if($request_domain!=$baseDomain){
            \App::abort(404, 'Resource not found');
        }
        
        \App::setLocale($languageModel->getDefaultLanguageCode());

        return $next($request);
    }
}
