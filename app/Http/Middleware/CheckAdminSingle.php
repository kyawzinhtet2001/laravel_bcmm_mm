<?php

namespace App\Http\Middleware;

use Closure;

class CheckAdminSingle
{
    /**
     * Handle an incoming request.
     * Check Admin is Single.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     * @author kyawzinhtet
     */
    public function handle($request, Closure $next)
    {
        # check admin relationship is single
        if(strtolower($request->relationship)==="single"){
            return $next($request);
        }
        return response()->json(["status"=>"NG","message"=>"You are not single"]);
    }
}
