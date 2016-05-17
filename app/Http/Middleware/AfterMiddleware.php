<?php

namespace App\Http\Middleware;

use Closure;
use App\Member;

class AfterMiddleware
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
        $myid = Session::get('id');
        $state = Member::find($myid)->state;

        if($state === 0){
            return $next($request);
        }else{
            return view('40x', ['errorCode'=>'3']);
        }
        
    }
}
