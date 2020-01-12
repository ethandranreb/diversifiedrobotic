<?php

namespace App\Http\Middleware;

use Closure;
use App;

class HttpsOnly
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
        $i = App::environment();

        if (!$request->secure() && App::environment() === 'local'):
            // return redirect()->secure($request->getRequestUri());
        endif;

        return $next($request);
    }
}
