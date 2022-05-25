<?php

namespace App\Http\Middleware;

use Closure;

class CheckAdmin
{
    /**
     * Check The Role is Admin and older than 50
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     * @author kyawzinhtet
     */
    public function handle($request, Closure $next)
    {
        $message='';
        # Check user is admin and age is greater than and equal 50 Check admin is singles
        if ($request->admin==1 && $request->age >= 50) {
            return $next($request);
        }
        #


        if(strtolower($request->relationship)!=="single"){
            $message.=" You are not single.";
        }
        if($request->age<50){
            $message.=" You are younger than 50.";
        }
        if(!$request->admin || $request->admin!=1){
            $message.=" You are not admin.";
        }
        return response()->json(['status' => 'NG', "message" => $message]);
    }
}
