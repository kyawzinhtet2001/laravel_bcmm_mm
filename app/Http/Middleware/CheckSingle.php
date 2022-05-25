<?php

namespace App\Http\Middleware;

use Closure;

class CheckSingle
{
    /**
     * Check Relationship is Single.
     * Check relationship is single and younger than 40
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return Closure|Response
     * @author kyawzinhtet
     *
     */
    public function handle($request, Closure $next)
    {
        $message="";
        #Check relationship is single and younger than 40
        if($request->admin===0 and strtolower($request->relationship)==="single" && $request->age<40){
            return $next($request);
        }
        if(strtolower($request->relationship)!=="single"){
            $message.=" You are not single.";
        }
        if($request->age>=40){
            $message.=" You are too old.";
        }
        return response()->json(["status"=>"NG","message"=>$message]);

    }
}
