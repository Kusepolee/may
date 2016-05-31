<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Member;
use Session;
use FooWeChat\Helpers\Helper;

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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $arr = ['admin'=>'no', 'position'=>'>=经理', 'department' => '>=运营部'];

        $a = new Auth;
        if(!$a->auth($arr)){
            return view('40x',['color'=>'warning', 'type'=>'3', 'code'=>'3.1']);
        }
        // ^ 身份验证

        $seek_string = $request->seek_string;
        $seek_array = explode('-',$seek_string);

        $seek_array[0] != '_not' ? $this->departmentsArray = explode("|", $seek_array[0]) : $this->departmentsArray = [];    
        $seek_array[1] != '_not' ? $this->positionsArray = explode("|", $seek_array[1]) : $this->positionsArray = [];    
        $seek_array[2] != '_not' ? $this->key = $seek_array[2] : $this->key = '';    

        

        $recs = Member::where('members.id', '>', 1)
                      ->where(function ($query) { 
                            if(count($this->departmentsArray)) $query->whereIn('members.department', $this->departmentsArray);
                            if(count($this->positionsArray)) $query->whereIn('members.position', $this->positionsArray);
                            if ($this->key != '' && $this->key != null) {
                                $query->where('members.name', 'LIKE', '%'.$this->key.'%');
                                $query->orWhere('members.work_id', 'LIKE', '%'.$this->key.'%');
                                $query->orWhere('members.mobile', 'LIKE', '%'.$this->key.'%');
                                $query->orWhere('members.content', 'LIKE', '%'.$this->key.'%');
                            }
                        })
                        ->where('members.show', 0)
                        ->where('members.private', 1)
                        ->orderBy('members.position')
                        ->orderBy('members.work_id')
                        ->orderBy('members.department')
                        ->leftJoin('members as m', 'members.created_by', '=', 'm.id')
                        ->leftJoin('departments', 'members.department', '=', 'departments.id')
                        ->leftJoin('positions', 'members.position', '=', 'positions.id')
                        ->leftJoin('config', 'members.gender', '=', 'config.id')
                        ->select('members.id', 'members.work_id', 'members.mobile', 'members.position', 'members.department', 'members.name', 'members.email', 'members.weixinid','members.qq', 'members.content', 'members.admin', 'members.state', 'm.name as created_byName', 'departments.name as departmentName', 'positions.name as positionName', 'config.name as genderName')
                        ->get();
    
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
