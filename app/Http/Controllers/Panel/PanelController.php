<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Session;
use Input;
use Image;
use App\Complaints;
use App\Member;
use App\Department;
use FooWeChat\Authorize\Auth;
use FooWeChat\Core\WeChatAPI;
use FooWeChat\Helpers\Helper;
use FooWeChat\Selector\Select;

class PanelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = Session::get('id');
        return view('panel.panel',['id'=>$id]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function complaints()
    {
        $id = Session::get('id');
        return view('panel.complaints',['id'=>$id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function complaintsStore(Requests\Complaints\ComplaintsRequest $request)
    {
        $input = $request->all();
        $input['state'] = 0;
        $id = Complaints::create($input)->id;

        //微信通知      
        $s = new Select;
        $w = new WeChatAPI;
        $h = new Helper;

        $rec = Complaints::find($id);
        if ($rec->type === 0) {
            $user_name = '匿名';
        } else {
            $user_name = Member::find($rec->user_id)->name;
        }
        $dp = Department::find($rec->target)->name;
        
        $body = '投诉人: '.$user_name.' 投送部门: '.$dp.' 内容: '.$rec->content;

        $dp_id = $rec->target;
        switch ($dp_id) {
            case 3:
                $array = [
              'user'       => '8',//8|6|2
              // 'department' => '资源部',
              // 'seek'       => '>=:经理@运营部',
              'self'       => 'own',
            ];
                break;
            case 6:
                $array = [
              'user'       => '8|6',//8|6|2
              // 'department' => '资源部',
              // 'seek'       => '>=:经理@运营部',
              'self'       => 'own',
            ];
                break;
            default:
                $array = [
              'user'       => '8|12',//8|6|2
              // 'department' => '资源部',
              // 'seek'       => '>=:经理@运营部',
              'self'       => 'own',
            ];
                break;
        }
        
        $url = 'http://'.$h->custom('url').'/panel/complaints/show/'.$id;
        $picurl = 'http://'.$h->custom('url').'/custom/image/news/advice.png';
        $arr_news = [['title'=>'投诉建议','description'=>$body,'url'=>$url,'picurl'=>$picurl]];
        
        $w->safe = 0;
        $w->sendNews($s->select($array), $arr_news);


        $arr = ['color'=>'success', 'type'=>'5','code'=>'5.1', 'btn1'=>'返回面板', 'link1'=>'/panel', 'btn2'=>'上传图片', 'link2'=>'/panel/complaints/image/set/'.$id];
        return view('note',$arr);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function image($id)
    {
        // return view('upload_image', ['path'=>'/panel/complaints/image/store', 'name'=>'面板首页', 'link'=>'/panel', 'resId'=>$id]);
        return view('panel.complaints_image', ['path'=>'/panel/complaints/image/store', 'name'=>'面板首页', 'link'=>'/panel', 'id'=>$id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function imageStore(Requests\Complaints\ImageRequest $request)
    {
        $file = Input::file('pic');
        $image = $file->getRealPath();

        $target = Complaints::find($request->id);
        $png_name = $target->id.'-'.time().'.png';

        Image::make($image)->save('upload/advice/'.$png_name);

        if($target->image != '' && $target->image != null){
            $target->update(['image'=>$target->image.'|'.$png_name]);
        }else{
            $target->update(['image'=>$png_name]);
        }
        $arr = ['color'=>'success', 'type'=>'5','code'=>'5.1', 'btn1'=>'返回面板', 'link1'=>'/panel', 'btn2'=>'继续上传图片', 'link2'=>'/panel/complaints/image/set/'.$request->id];
        return view('note',$arr);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function complaintsRecord()
    {
        $id = Session::get('id');
        $a = new Auth;
        $h = new Helper;

        if($a->auth(['admin'=>'no', 'position'=>'>=总经理', 'department'=>'>=总经理'])){
            $rec = Complaints::orderBy('created_at', 'desc')
                            ->leftjoin('members', 'complaints.user_id', '=', 'members.id')
                            ->leftjoin('departments', 'complaints.target', '=', 'departments.id')
                            ->select('complaints.*', 'members.name as userName', 'departments.name as dpName')
                            ->paginate(30);
            
        }elseif($a->auth(['admin'=>'no', 'position'=>'>=总监', 'department'=>'>=运营部'])){
            $rec = Complaints::where('target', 6)
                            ->orderBy('created_at', 'desc')
                            ->leftjoin('members', 'complaints.user_id', '=', 'members.id')
                            ->leftjoin('departments', 'complaints.target', '=', 'departments.id')
                            ->select('complaints.*', 'members.name as userName', 'departments.name as dpName')
                            ->paginate(30);

        }elseif($a->auth(['admin'=>'no', 'position'=>'>=总监', 'department'=>'>=监察部'])){
            $rec = Complaints::where('target', 10)
                            ->orderBy('created_at', 'desc')
                            ->leftjoin('members', 'complaints.user_id', '=', 'members.id')
                            ->leftjoin('departments', 'complaints.target', '=', 'departments.id')
                            ->select('complaints.*', 'members.name as userName', 'departments.name as dpName')
                            ->paginate(30);

        }else{
            $rec = Complaints::where('user_id', $id)
                            ->orderBy('created_at', 'desc')
                            ->leftjoin('members', 'complaints.user_id', '=', 'members.id')
                            ->leftjoin('departments', 'complaints.target', '=', 'departments.id')
                            ->select('complaints.*', 'members.name as userName', 'departments.name as dpName')
                            ->paginate(30);

        }
        foreach ($rec as $out) {
            if($out->type === 0) $out->userName = '匿名';
        }

        return view('panel.complaints_record', ['rec'=>$rec]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function complaintsShow($id)
    {
        $user_id = Session::get('id');
        $rec = Complaints::find($id);
        $a = new Auth;

        if($a->auth(['admin'=>'no', 'position'=>'>=总经理', 'department'=>'>=总经理'])){
            if ($rec->type === 0) {
                    $user_name = '匿名';
                } else {
                    $user_name = Member::find($rec->user_id)->name;
                }
                $dp = Department::find($rec->target)->name;
                if($rec->image != '' && $rec->image != null){
                    $img = explode('|', $rec->image);
                }else{
                    $img = 0;
                }
        }elseif($a->auth(['admin'=>'no', 'position'=>'>=总监', 'department'=>'>=运营部'])){
            if ($rec->type === 0) {
                    $user_name = '匿名';
                } else {
                    $user_name = Member::find($rec->user_id)->name;
                }
                $dp = Department::find($rec->target)->name;
                if($rec->image != '' && $rec->image != null){
                    $img = explode('|', $rec->image);
                }else{
                    $img = 0;
                }
        }elseif($a->auth(['admin'=>'no', 'position'=>'>=总监', 'department'=>'>=监察部'])){
            if ($rec->type === 0) {
                    $user_name = '匿名';
                } else {
                    $user_name = Member::find($rec->user_id)->name;
                }
                $dp = Department::find($rec->target)->name;
                if($rec->image != '' && $rec->image != null){
                    $img = explode('|', $rec->image);
                }else{
                    $img = 0;
                }
        }else{
            if($user_id != $rec->user_id){
                return view('40x',['color'=>'warning', 'type'=>'3', 'code'=>'3.1']);
                exit;
            }else{
                if ($rec->type === 0) {
                    $user_name = '匿名';
                } else {
                    $user_name = Member::find($rec->user_id)->name;
                }
                $dp = Department::find($rec->target)->name;
                if($rec->image != '' && $rec->image != null){
                    $img = explode('|', $rec->image);
                }else{
                    $img = 0;
                }
            }
        }
        return view('panel.complaints_show', ['rec'=>$rec, 'user_name'=>$user_name, 'dp'=>$dp, 'img'=>$img]);
        
    }
}
