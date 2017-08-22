<?php namespace App\Http\Middleware;
use Closure;
class CloudFlareProxies {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $proxyIps = \Cache::remember('cloudFlareProxyIps', 1440, function () {
            $url = 'https://www.cloudflare.com/ips-v4';
            $ips = file_get_contents($url);
            return array_filter(explode("\n", $ips));
        });

        $request->setTrustedProxies($proxyIps);

        return $next($request);
    }
}