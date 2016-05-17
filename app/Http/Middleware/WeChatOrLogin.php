<?php

namespace App\Http\Middleware;

use App\Member;
use Closure;
use Cookie;
use FooWeChat\Core\WeChatAPI;
use Input;
use Request;
use Session;

class WeChatOrLogin
{
    /**
     * 微信或者网页使用者验证中间件:
     * 访问 --- session -----> 有 -> 完成
     *                   |--> 无 但有cookie -> 以cookie信息设置session -> 完成
     *                   |--> 无session 也无cookie --> 非微信浏览器 --> 跳转登录页 ...
     *                                             |--> 微信 --> 带code --> 以code信息设置session cookie -> 完成
     *                                                       |     |_____________________________
     *                                                       |                                   |
     *                                                       |--> 不带code --> 获取code,重定向--->-
     *                                                                                         
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];

        if(Session::has('id')){
            return $next($request);

        }else if(Cookie::get('id') && Session::has('id') === false){

            $id = Cookie::get('id');
            $rec = Member::find($id);

            if(count($rec)){

                if(!Session::has('id')) Session::put('id', $rec->id);
                if(!Session::has('name')) Session::put('name', $rec->name);

                return $next($request);

            }else{
                return view('40x',['color'=>'red', 'type'=>'1', 'code'=>'1.2']);

            }

        }else{
            if (strpos($user_agent, 'MicroMessenger') === false) {
                // 非微信浏览器
                $redirect_path = Request::path();
                return view('login')->with('redirect_path', $redirect_path);

            } else {
                //微信内
                if(Input::get('code')){
                    //带code
                    $fnc = new WeChatAPI;
                    $fnc->code = Input::get('code');
                    $fnc->oAuth2UserInfo();
                    $fnc->weChatUserSetCookieAndSession();

                    return $next($request);
                    
                }else{
                    //不带code
                    $fnc = new WeChatAPI;
                    $fnc->oAuth2();
                }
            }
        }
    }
}
