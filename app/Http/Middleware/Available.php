<?php

namespace App\Http\Middleware;

use App\Member;
use Closure;
use Session;

class Available
{
    /**
     * 检查状态
     *
     * 1. 未锁定
     * 2. 未加删除标记
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Session::has('id')){
            $id = Session::get('id');
        }else{
            die('Middleware\Available: need Login');
        }
        
        $rec = Member::find($id);

        if($rec->state === 0 && $rec->show === 0) {
            return $next($request);
        }else{
            return view('40x',['color'=>'red', 'type'=>'2', 'code'=>'2.5']);
        }
    }

    /**
    * other functions
    *
    */
}






