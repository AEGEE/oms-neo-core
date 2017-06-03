<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;

class SeoURLMiddleware
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
        $seourls = array_except(func_get_args(), [0,1]);
        $route = $request->route();
        foreach($seourls as $seourl) {
            if ($route->hasParameter($seourl) && !$this->isInteger($route->getParameter($seourl))) {
                // argument is not an integer, thus not an ID, might be seourl.
                $new = $this->seoSearch($route->getParameter($seourl));
                $route->setParameter($seourl, $new);
            }
        }
        return $next($request);
    }

    function isInteger($input){
        return(ctype_digit(strval($input)));
    }

    public function seoSearch($seourl) {
        return User::where('seo_url', $seourl)->firstOrFail()->id;
    }
}
