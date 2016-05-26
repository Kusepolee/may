<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Member;
use Session;
use FooWeChat\Helpers\Helper;
use iscms\Alisms\SendsmsPusher as Sms;

class OaController extends Controller
{
    public function __construct(Sms $sms)
    {
       $this->sms=$sms;
    }
    /**
    * 发送手机短信
    *
    */
    public function sendSms()
    {
        $array = ['customer'=>'1234'];
        $content = json_encode($array, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);

        $phone = '13901752021';
        $name = '大鱼测试';
        $code = 'SMS_8961325';
        $result = $this->sms->send($phone,$name,$content,$code);
        print_r($result);
    }

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

        if($id !=0 ){
            $rec = Member::find($id);
            $code = $rec->wechat_code;
            $name = $rec->name;
            $qrcode = $h->getWechatQrcodeInfo($code);
        }else{
            $code = $h->custom('wechat_code');
            $name = $h->custom('nic_name');
            $qrcode = $h->getWechatQrcodeInfo($code);
        }

        return view('qrcode', ['qrcode'=>$qrcode, 'name'=>$name]);
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
