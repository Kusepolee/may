<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Member;
use App\Http\Controllers\Controller;
use FooWeChat\Authorize\Auth;
use FooWeChat\Core\WeChatAPI;
use FooWeChat\Selector\Select;
use Logie;
use Mail;
use Session;

class NoticeController extends Controller
{
    protected $departmentsArray;
    protected $positionsArray;
    protected $key;

    /**
     * 通知查询清单中的员工
     *
     * @return wechat message or null
     */
    public function member(Request $request)
    {
        $arr = ['position'=>'>员工'];

        $a = new Auth;
        if(!$a->auth($arr)){
            return view('40x',['color'=>'warning', 'type'=>'3', 'code'=>'3.1']);
        }
        // ^ 身份验证

        $seek_string = $request->seek_string_notice;
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
                        ->select('members.id', 'members.work_id')
                        ->get();
        $work_ids = [];

        $no_rigths = [];
        $not_follow = [];
        
        $arr = ['position'=>'>=经理', 'department' => '>=运营部'];

        $w = new WeChatAPI;

        if($a->auth($arr)){
            foreach ($recs as $rec) {
                if($w->hasFollow($rec->id)){
                    $work_ids[] = $rec->work_id;
                }else{
                    $not_follow[] = $rec->work_id;
                }  
            }

        }else{
            foreach ($recs as $rec) {
                if($a->hasRights($rec->id)){
                    if($w->hasFollow($rec->id)){
                        $work_ids[] = $rec->work_id;
                    }else{
                        $not_follow[] = $rec->work_id;
                    } 

                }else{
                    $no_rigths[] = $rec->work_id;
                }
            }
        }

        $my_work_id = Member::find(Session::get('id'))->work_id;

        //发送统计结果
        $body = '[微信通知群发统计] 总人数: '.count($recs).'其中无权发送的: '.count($no_rigths). ', 微信未关注的:'.count($not_follow).', 已经成功发送:'.count($work_ids);

        $s = new Select;
        $w->sendText($s->select(['user'=>$my_work_id]), $body);

        //群发
        if(count($work_ids)){
            $work_ids_str = implode('|',$work_ids);
            $w->sendText($s->select(['user'=>$work_ids_str]), $request->notice);
        }

        //日志
        Logie::add(['warning', '群体发消息,'.$request->notice]);

        //页面
        $arr = ['color'=>'success', 'type'=>'5','code'=>'5.2', 'btn'=>'返回', 'link'=>'/member'];
        return view('note',$arr);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function mail($id)
    {
        Mail::send('emails.welcome', array(), function($message)
        {
            $message
                ->to('7569300@qq.com')
                ->from('notice@automail.henjou.com')
                ->subject('TEST');
        });
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
