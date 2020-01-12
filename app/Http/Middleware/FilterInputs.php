<?php

namespace App\Http\Middleware;

use Closure;

class FilterInputs
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
        $values = $request->all();
        $values = $this->filter_values($values);

        foreach($values as $key => $value): $request->merge([$key => $value]);
        endforeach;

        return $next($request);
    }

    private function filter_values(array &$array)
    {
        array_walk_recursive($array, function (&$value) {
            $value = filter_var(trim($value), FILTER_SANITIZE_STRING, FILTER_NULL_ON_FAILURE);
        });

        return $array;
    }
}
