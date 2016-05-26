<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Member;
use App\Position;
use Config;
use Cookie;
use FooWeChat\Authorize\Auth;
use FooWeChat\Core\WeChatAPI;
use FooWeChat\Helpers\Helper;
use FooWeChat\Selector\Select;
use Hash;
use Illuminate\Http\Request;
use Input;
use Logie;
use Session;

class MemberController extends Controller
{
    protected $departmentsArray;
    protected $positionsArray;
    protected $key;

    /**
     * 登录
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Requests\LoginRequest $request)
    {

        $input = $request->all();
        $workid = $request->get('workid');
        $password = $request->get('password');

        $rec = Member::where('work_id', $workid)
                            ->orWhere('mobile', $workid)
                            ->first();

        if($request->has('redirect_path')){
            $redirect_path = $request->get('redirect_path');
        }else{
            $redirect_path = '/';
        }

        if(count($rec)){
            if (Hash::check($password, $rec->password)) {
                if($rec->state === 0){
                    //账号状态正常
                    if(!Session::has('id')) Session::put('id', $rec->id);
                    if(!Session::has('name')) Session::put('name', $rec->name);

                    //Cookie::queue('id', $rec->id, 20160);
                    
                    return redirect($redirect_path);

                }else{
                    return view('login',[
                        'type'=>'2',
                        'code'=>'2.1',
                        'redirect_path'=>$redirect_path
                    ]);

                }
                
            }else{
                return view('login',[
                        'type'=>'2',
                        'code'=>'2.2',
                        'redirect_path'=>$redirect_path
                ]);

            }

        }else{
            
            return view('login',[
                        'type'=>'1',
                        'code'=>'1.2',
                        'redirect_path'=>$redirect_path
            ]);
        }

    }
    
    /**
     * 退出
     *
     * @return mix
     */
    public function logout()
    {

        if (Session::has('id')) Session::flush();
        if (Cookie::get('id')) Cookie::forget('id');

        return redirect('/');
    }

    /**
     * 用户管理
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $outs = Member::where('members.id', '>', 1)
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
                        ->paginate(30);

        return view('member', ['outs'=>$outs, 'dp'=>$this->departmentsArray, 'pos'=>$this->positionsArray, 'key'=>$this->key]);
    }

    /**
    * 查询
    */
    public function memberSeek(Requests\MemberSeekRequest $request)
    {
        $seek = $request->all();
        

        if ($seek['dp_val'] == 0 && $seek['pos_val'] == 0 && ($seek['key'] =='' || $seek['key'] == null)) {
            //go on
        }else{
            $h = new Helper;

            if($seek['dp_val'] != 0) {
                $departments = $h->getDepartmentsArray($seek['dp_operator'], $seek['dp_val']);
                if(count($departments)){
                    $this->departmentsArray = $departments;
                }else{
                    $arr = ['color'=>'info', 'type'=>'6','code'=>'6.1', 'btn'=>'返回用户管理', 'link'=>'/member'];
                    return view('note',$arr);
                }
            }

            if($seek['pos_val'] != 0) {
                $positions = $h->getPositionsArray($seek['pos_operator'], $seek['pos_val']);
                if(count($positions)){
                    $this->positionsArray = $positions;
                }else{
                    $arr = ['color'=>'info', 'type'=>'6','code'=>'6.1', 'btn'=>'返回用户管理', 'link'=>'/member'];
                    return view('note',$arr);
                }
            }

            if($seek['key'] != '' && $seek['key'] != null) $this->key= $seek['key'];
        }

        return $this->index();
    }

    /**
     * 初始化微信用户群
     *
     * @return \Illuminate\Http\Response
     */
    public function weChatInitUsers()
    {
        $arr = ['admin'=>'only'];

        $a = new Auth;
        if(!$a->auth($arr)){
            return view('40x',['color'=>'warning', 'type'=>'3', 'code'=>'3.1']);
            exit;
        }
        // ^ 身份验证

        $api = new WeChatAPI;
        $api->initUsers();

        // 日志
        Logie::add(['important', '初始化微信用户群']);
    }

    /**
     * 添加用户: 表单
     *
     * 用户表单
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $arr = ['position'=>'>=经理'];

        $a = new Auth;
        if(!$a->auth($arr)){
            return view('40x',['color'=>'warning', 'type'=>'3', 'code'=>'3.1']);
            exit;
        }
        // ^ 身份验证

        return view('member_form', ['act'=>'添加用户']);
    }

    /**
     * 添加用户
     *
     * 1. 本地数据库
     * 2. 微信用户
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\MemberStoreRequest $request)
    {
        $arr = ['position'=>'>=经理'];

        $a = new Auth;
        if(!$a->auth($arr)){
            return view('40x',['color'=>'warning', 'type'=>'3', 'code'=>'3.1']);
            exit;
        }
        // ^ 身份验证

        $input = $request->all();

        $h = new Helper;
        $my_work_id = $h->getWorkId();

        $input['work_id'] = $my_work_id;
        $input['state'] = 0;
        $input['show'] = 0;
        $input['new'] = 0;
        $input['admin'] = 1;
        $input['created_by'] = intval(Session::get('id'));
        $input['password'] = bcrypt($input['password']);;

        Member::create($input);

        $position = Position::find($input['position']);
        $positionName = $position->name;

        $wechatAarry = [
                        'userid'     => $my_work_id,
                        'name'       => $input['name'],
                        'department' => $input['department'],
                        'position'   => $positionName,
                        'mobile'     => $input['mobile'],
                        'gender'     => $input['gender'],
                        'email'      => $input['email'],
                        'weixinid'   => $input['weixinid'],
                        ];
        if($input['private'] == 1){
            $wechatAPI = new WeChatAPI;
            $wechatAPI->createUser($wechatAarry);
        }

        //日志
        Logie::add(['notice', '新建用户: 工号'.$my_work_id.','.$input['name']]);

        $arr = ['color'=>'success', 'type'=>'5','code'=>'5.1', 'btn'=>'用户管理', 'link'=>'/member'];
        return view('note',$arr);
    }

    /**
     * 锁定用户
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function lock($id)
    {
        $arr = ['position'=>'>=经理'];

        $a = new Auth;
        if(!$a->auth($arr) || !$a->hasRights($id)){
            return view('40x',['color'=>'warning', 'type'=>'3', 'code'=>'3.1']);
            exit;
        }
        // ^ 身份验证

        $target = Member::find($id);
        $target->update(['state' => 1]);
        $home = '/member/show/'.$id;

        //日志
        Logie::add(['notice', '锁定用户:'.$target->work_id.','.$target->name]);

        $arr = ['color'=>'success', 'type'=>'5','code'=>'5.1', 'btn'=>'用户管理', 'link'=>'/member', 'btn1'=>'用户信息', 'link1'=>$home];
        return view('note',$arr);

        
    }
    /**
     * 解除锁定
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function unlock($id)
    {
        $arr = ['position'=>'>=经理'];

        $a = new Auth;
        if(!$a->auth($arr) || !$a->hasRights($id)){
            return view('40x',['color'=>'warning', 'type'=>'3', 'code'=>'3.1']);
            exit;
        }
        // ^ 身份验证

        $target = Member::find($id);
        $target->update(['state' => 0]);
        $home = '/member/show/'.$id;

        //日志
        Logie::add(['notice', '锁定用户:'.$target->work_id.','.$target->name]);

        $arr = ['color'=>'success', 'type'=>'5','code'=>'5.1', 'btn'=>'用户管理', 'link'=>'/member', 'btn1'=>'用户信息', 'link1'=>$home];
        return view('note',$arr);
    }

    /**
     * 去除管理员权限
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function adminLost($id)
    {
        $arr = ['root'=>'only'];

        $a = new Auth;
        if(!$a->auth($arr)){
            return view('40x',['color'=>'warning', 'type'=>'3', 'code'=>'3.1']);
            exit;
        }
        // ^ 身份验证

        $target = Member::find($id);
        $target->update(['admin' => 1]);

        //日志
        Logie::add(['notice', '解除管理员:'.$target->work_id.','.$target->name]);

        $home = '/member/show/'.$id;

        $arr = ['color'=>'success', 'type'=>'5','code'=>'5.1', 'btn'=>'用户管理', 'link'=>'/member', 'btn1'=>'用户信息', 'link1'=>$home];
        return view('note',$arr);
    }
    /**
     * 授于管理员权限
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function adminGet($id)
    {
        $arr = ['root'=>'only'];

        $a = new Auth;
        if(!$a->auth($arr)){
            return view('40x',['color'=>'warning', 'type'=>'3', 'code'=>'3.1']);
            exit;
        }
        // ^ 身份验证

        $target = Member::find($id);
        $target->update(['admin' => 0]);

        //日志
        Logie::add(['notice', '授于管理员:'.$target->work_id.','.$target->name]);

        $home = '/member/show/'.$id;

        $arr = ['color'=>'success', 'type'=>'5','code'=>'5.1', 'btn'=>'用户管理', 'link'=>'/member', 'btn1'=>'用户信息', 'link1'=>$home];
        return view('note',$arr);
    }

    /**
     * 用户信息
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $rec = Member::leftJoin('departments', 'members.department', '=', 'departments.id')
                    ->leftJoin('members as a', 'members.created_by', '=', 'a.id')
                    ->leftJoin('positions', 'members.position', '=', 'positions.id')
                    ->select('members.*', 'a.name as created_byName', 'departments.name as departmentName', 'positions.name as positionName')
                    ->find($id);
        //日志
        Logie::add(['info', '查看用户资料:'.$rec->work_id.','.$rec->name]);

        return view('member_show', ['rec'=>$rec]);
    }

    /**
     * 修改用户
     *
     * 1. 用户表单
     * 2. 填充数据
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $arr = ['position'=>'>=经理'];

        $a = new Auth;

        //本人允许
        $arr = $a->addSelf($arr, 'user', $id);

        if(!$a->auth($arr) || !$a->hasRights($id, 0)){
            return view('40x',['color'=>'warning', 'type'=>'3', 'code'=>'3.1']);
            exit;

        }
        // ^ 身份验证

        $rec = Member::leftJoin('departments', 'members.department', '=', 'departments.id')
                       ->leftJoin('positions', 'members.position', '=', 'positions.id')
                       ->select('members.*', 'departments.name as departmentName', 'positions.name as positionName')
                       ->find($id);
        return view('member_form', ['act'=>'修改资料', 'rec'=>$rec]);
    }

    /**
     * 修改用户信息
     *
     * 1. 更新数据库
     * 2. 更新微信通讯录
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\MemberUpdateRequest $request, $id)
    {
        $arr = ['position'=>'>=经理'];

        $a = new Auth;

        //本人允许
        $arr = $a->addSelf($arr, 'user', $id);

        if(!$a->auth($arr) || !$a->hasRights($id, 0)){
            return view('40x',['color'=>'warning', 'type'=>'3', 'code'=>'3.1']);
            exit;

        }
        // ^ 身份验证

        $update = $request->all();

        unset($update['_token']);
        unset($update['password_confirmation']);

        if($update['password'] == '' || $update['password'] == null){
            unset($update['password']);
        }else{
            $update['password'] = bcrypt($update['password']);
        }

        $old = Member::find($id);

        $arr = [];

        if($update['name'] != $old->name) $arr = array_add($arr, 'name', $update['name']);
        if($update['department'] != $old->department) $arr = array_add($arr, 'department', $update['department']);

        if($update['position'] != $old->position) {
            $a = $update['position'];
            $position = Position::find($a)->name;
            $arr = array_add($arr, 'position', $position); 
        }

        if($update['mobile'] != $old->mobile) $arr = array_add($arr, 'mobile', $update['mobile']);
        if($update['gender'] != $old->gender) $arr = array_add($arr, 'gender', $update['gender']);
        if($update['email'] != $old->email) $arr = array_add($arr, 'email', $update['email']);
        if($update['weixinid'] != $old->weixinid) $arr = array_add($arr, 'weixinid', $update['weixinid']);

        //若微信相关字段更新
        if(count($arr)){
            $userid = $old->work_id;
            $arr = array_add($arr, 'userid', $userid);
            $wechatAPI = new WeChatAPI;
            $wechatAPI->updateUser($arr);
        }

        $target = Member::find($id);
        $target->update($update);

        //日志
        Logie::add(['info', '修改用户:'.$target->work_id.','.$target->name]);

        $home = '/member/show/'.$id;

        $arr = ['color'=>'success', 'type'=>'5','code'=>'5.1', 'btn'=>'用户管理', 'link'=>'/member', 'btn1'=>'用户信息', 'link1'=>$home];
        return view('note',$arr);
    }

    /**
    * 删除表单
    *
    * @param null
    *
    * @return view
    */
    public function deleteNote($id)
    {
        $abort = '/member/show/'.$id;
        $delete = '/member/delete_do/'.$id;

        $arr = ['color'=>'danger', 'type'=>'4','code'=>'4.1', 'btn'=>'放弃', 'link'=>$abort, 'btn1'=>'确定删除', 'link1'=>$delete];
        return view('note',$arr);
    }

    /**
     * 删除用户
     *
     * 1. root, admin : 删除用户 --> 其他表存在记录 -> 删除微信用户, 保留本地数据库记录
     *                          |
     *                          |-> 不存在 -> 删除本地数据和微信用户
     * 1. 其他用户: 隐藏用户
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $arr = ['position'=>'>=经理'];

        $a = new Auth;
        if(!$a->auth($arr) || !$a->hasRights($id)){
            return view('40x',['color'=>'warning', 'type'=>'3', 'code'=>'3.1']);
            exit;
        }
        // ^ 身份验证
        $h = new Helper;

        // 检查存在: ['table'=>'list1|list2|list3', 'table1'=>'list']
        $t =['members'=>'created_by']; 

        if($a->isRoot() || $a->isAdmin()){  
            $target = Member::find($id);
            $work_id = $target->work_id;

            if(!$h->exsitsIn($t, $id)) $target->delete();
    
            $wechat = new WeChatAPI;
            $wechat->deleteUser($work_id);

            //日志
            Logie::add(['important', '删除用户-删除本地和微信:'.$target->work_id.','.$target->name]);


        }else{
            $target = Member::find($id);
            $target->update(['show'=>1]);

            //日志
            Logie::add(['important', '删除用户-隐藏:'.$target->work_id.','.$target->name]);
        }

        $arr = ['color'=>'success', 'type'=>'5','code'=>'5.1', 'btn'=>'用户管理', 'link'=>'/member'];
        return view('note',$arr);

    }

    /**
    * 密码修改
    *
    */
    public function passwordReset(Requests\PasswordResetRquest $request, $id)
    {
        $new_password = $request->password;
        $redirect_path = '/'.$request->path;
        $target = Member::find($id);
        $target->update(['new'=>1, 'password'=>bcrypt($new_password)]);

        //日志
        Logie::add(['info', '修改密码:'.$target->work_id.','.$target->name]);

        return redirect($redirect_path);
  
    }

    /**
    * test
    */
    public function test()
    {
        $arr = ['user'=>'8', 'department'=>'市场部|技术部', 'seek'=>'>=:经理@生产部', 'self'=>'sub+'];
        $a = new Select;
        print_r($a->select($arr));
    }

    /**
    * other functions
    *
    */
}

















