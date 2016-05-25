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
     * 3. 初次访问: 强制修改密码
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
        $path = $request->path();
        
        $rec = Member::find($id);
        if (!count($rec)) return view('40x',['color'=>'red', 'type'=>'2', 'code'=>'2.5']);
        
        $path = $request->path();
        $url = 'member/password/reset/'.$id;
        $method = $request->method();


        if($rec->state === 0 && $rec->show === 0){
            if($rec->new === 0){
                if ($path == $url && $method == 'POST') {
                    return $next($request);
                }else{
                    return view('password_form', ['act'=>'密码修改', 'id'=>$rec->id]);
                }
            }else{
                return $next($request);
            }

        }else{
            return view('40x',['color'=>'red', 'type'=>'2', 'code'=>'2.5']);
        }
    }

    /**
    * other functions
    *
    */
}






