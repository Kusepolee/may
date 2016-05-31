<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Member;
use App\Http\Controllers\Controller;
use iscms\Alisms\SendsmsPusher as Sms;

class NoticeController extends Controller
{
    protected $smsSignature = '恒久滚塑';

    /**
    * SMS constract
    *
    */
    public function __construct(Sms $sms)
    {
       $this->sms=$sms;
    }

    /**
    * SMS
    *
    * $array = ['signature'=>'恒久滚塑', 'templet'=>'SMS_10160512', 'content'=>['mobile'=>'1300000,1200000', 'name'=>'某人']];
    *
    * 一次最大调用: 200
    *
    * @param $array
    *
    * @return sms message
    *
    */
    public function sendSms()
    {
        echo "fuck";
        
        //$this->checkSmsArray($array);

        //if(array_has('signature', $array)) $this->smsSignature = array_get('signature', $array);

        $array = ['name'=>'RainMan', 'code'=>'12345'];
        $content = json_encode($array, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        $phone = '13901752021';
        $name = '恒久滚塑';
        $code = 'SMS_10160512';
        //$result = $this->sms->send($phone,$name,$content,$code);
        //print_r($result);
    }


    /**
     * SMS array 验证
     *
     * @return \Illuminate\Http\Response
     */
    public function checkSmsArray($array)
    {

        if(!array_has('templet', $array)) die('FooWechat\NoticeControler\sendSms: 模板缺失');
        if(!array_has('content', $array)) die('FooWechat\NoticeControler\sendSms: 内容缺失');

        $content_array = array_get('content', $array);
        if(!array_has('mobile', $content_array))  die('FooWechat\NoticeControler\sendSms: 手机号缺失');
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
