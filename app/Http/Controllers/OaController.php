<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Member;
use Cookie;
use FooWeChat\Helpers\Helper;
use Session;

class OaController extends Controller
{
    protected $departmentsArray;
    protected $positionsArray;
    protected $key;

    /**
     * 二维码
     *
     * @param null or id of members
     *
     * @return qrcode
     */
    public function qrcode($id=0)
    {
        $h = new Helper;
        $qrcode = '';

        if($id === 0){
            $code = $h->custom('wechat_code');
            $name = $h->custom('nic_name');
            $qrcode = $h->getWechatQrcodeInfo($code);
            //$png = base_path().'/public/custom/image/qrcode_png.png';
            $png = '/public/custom/image/qrcode_png.png';
        }elseif($id === 'wifi'){
            $qrcode = [
                'encryption' => 'WPA-PSK/WPA2-PSK',
                'ssid' => 'LinkDrive.com',
                'password' => '83082999'
            ];
            $name = '公司WIFI';
            $png = 'wifi';
        }else{
            $rec = Member::find($id);
            $code = $rec->wechat_code;
            $name = $rec->name;
            $qrcode = $h->getWechatQrcodeInfo($code);
            //$base_path_img =  base_path().'/public/upload/member/';
            $base_path_img =  '/public/upload/member/';
            $rec->img == '' || $rec->img == null ? $png = 0 : $png = $base_path_img.$rec->img;
        }

        return view('qrcode', ['qrcode'=>$qrcode, 'name'=>$name, 'png'=>$png]);
    }

    /**
    * 电子名片
    *
    */
    public function vcard($id=0)
    {
        if($id === 0) {
            if(Session::has('id')){
                $id = Session::get('id');
            }else{
                die('OaController\getVcard: 需要登录');
            }
        }

        $rec = Member::leftJoin('departments', 'members.department', '=', 'departments.id')
                    ->leftJoin('positions', 'members.position', '=', 'positions.id')
                    ->select('members.name', 'members.mobile', 'members.email', 'departments.name as departmentName', 'positions.name as positionName')
                    ->find($id);

        if(!count($rec)) die('OaController\getVcard: 错误');
        return view('vcard', ['rec'=>$rec]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function cookieClear()
    {
        //Cookie::forget('id');
        Cookie::queue('id', null , -1); // 销毁
        $url = '/member/show';
        return redirect($url);
    }


}
