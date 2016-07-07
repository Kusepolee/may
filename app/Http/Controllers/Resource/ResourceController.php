<?php

namespace App\Http\Controllers\Resource;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Resource;
use App\Member;
use App\FormConfig;
use App\ResourceRecord;
use Config;
use Cookie;
use DB;
use FooWeChat\Authorize\Auth;
use FooWeChat\Core\WeChatAPI;
use FooWeChat\Helpers\Helper;
use FooWeChat\Selector\Select;
use Hash;
use Input;
use Logie;
use Session;

class ResourceController extends Controller
{
    protected $rescTypesArray;
    protected $key;

    /**
     * 资源管理
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $outs = Resource::where(function ($query) { 
                            if(count($this->rescTypesArray)) $query->whereIn('resources.type', $this->rescTypesArray);
                            if ($this->key != '' && $this->key != null) {
                                $query->where('resources.name', 'LIKE', '%'.$this->key.'%');
                            }
                        })
                          ->where('resources.show', 0)
                          ->orderBy('updated_at', 'desc')
                          ->leftJoin('config as a', 'resources.type', '=', 'a.id')
                          ->leftJoin('config as b', 'resources.unit', '=', 'b.id')
                          ->leftJoin('members', 'resources.createBy', '=', 'members.id')
                          ->select('resources.*', 'a.name as typeName', 'b.name as unitName', 'members.name as createByName')
                          ->paginate(30);

        return view('resource.resource', ['outs'=>$outs, 'rescType'=>$this->rescTypesArray, 'key'=>$this->key]);
    }

    /**
    * 查询
    */
    public function resourceSeek(Requests\Resource\ResourceSeekRequest $request)
    {
        $seek = $request->all();

        if ($seek['rescType_val'] == 0 && ($seek['key'] =='' || $seek['key'] == null)) {
            //go on
        }else{

            if($seek['rescType_val'] != 0) {
                $rescTypes = $seek['rescType_val'];
                if(count($rescTypes)){
                    $this->rescTypesArray = [$rescTypes];
                }else{
                    $arr = ['color'=>'info', 'type'=>'6','code'=>'6.1', 'btn'=>'返回资源管理', 'link'=>'/resource'];
                    return view('note',$arr);
                }
            }

            if($seek['key'] != '' && $seek['key'] != null) $this->key= $seek['key'];
        }
       
        return $this->index();       
    }
    
    /**
     * 登记资源
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $arr = ['department'=>'=资源部'];
        $a = new Auth;
        if(!$a->auth($arr)){
            return view('40x',['color'=>'warning', 'type'=>'3', 'code'=>'3.1']);
            exit;
        }
        return view('resource.resource_form', ['act'=>'资源登记']);
    }

    /**
     * 保存登记资源
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\Resource\ResourceStoreRequest $request)
    {
        $input = $request->all();

        $input['createBy'] = intval(Session::get('id'));
        $input['remain'] = 0;
        $input['show'] = 0;
        if ($request->notice != 0 || $request->alert != 0) {
            $input['state'] = 0;
        } else {
            $input['state'] = 4;
        }

        $id = Resource::create($input)->id;

        //日志
        Logie::add(['notice', '新建资源: '.$input['name'].'/'.$input['model']]);

        //微信消息
        $user = Session::get('name');

        $body_1 = $user.' 新建: '.$input['name'].'('.$input['model'].')';
        if($input['notice'] != 0 && $input['alert'] != 0){
            $body_2 = "\n".'提醒值: '.$input['notice'].' 报警值: '.$input['alert'];
        }else{
            $body_2 = '';
        }
        $body = '[资源新建] '.$body_1.$body_2;

        $s = new Select;
        $w = new WeChatAPI;
        $h = new Helper;

        $array = [
               'user'       => '15',
              //'department' => '资源部',
              //'seek'       => '>=:经理@资源部',
              //'self'       => 'own',
            ];
        
        $url = 'http://'.$h->custom('url').'/resource/show/'.$id;
        $picurl = 'http://'.$h->custom('url').'/custom/image/news/resource.png';
        $arr_news = [['title'=>'资源','description'=>$body,'url'=>$url,'picurl'=>$picurl]];
        
        $w->safe = 0;
        $w->sendNews($s->select($array), $arr_news);

        return redirect('/resource');
    }

    /**
     * 资源信息及附加功能展示
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $rec = Resource::orderBy('updated_at', 'DESC')
                         ->leftJoin('config as a', 'resources.type', '=', 'a.id')
                         ->leftJoin('config as b', 'resources.unit', '=', 'b.id')
                         ->leftJoin('members', 'resources.createBy', '=', 'members.id')
                         ->select('resources.*', 'a.name as typeName', 'b.name as unitName', 'members.name as createByName')
                         ->find($id);

        $remain = $this->getRemain($id);

        $resource_records = ResourceRecord::where('resource', $id)
                              ->leftJoin('members', 'resource_records.to', '=', 'members.id')
                              ->leftJoin('config as a', 'resource_records.type', '=', 'a.id')
                              ->leftJoin('config as b', 'resource_records.for', '=', 'b.id')
                              ->leftJoin('resources', 'resource_records.resource', '=', 'resources.id')
                              ->select('resource_records.*', 'members.name as memberName', 'a.name as typeName', 'b.name as forName', 'resources.name as resourceName')
                              ->orderBy('created_at', 'DESC')
                              ->paginate(15);

        if(!count($resource_records)) $resource_records = 0;

        return view('resource.resource_show', ['rec'=>$rec, 'resource_records'=>$resource_records, 'remain'=>$remain]);
    }

    /**
     * 资源信息修改
     * *填写表单
     */
    public function edit($id)
    {
        // 身份验证
        $arr = ['department'=>'=资源部'];
        $a = new Auth;
        if(!$a->auth($arr)){
            return view('40x',['color'=>'warning', 'type'=>'3', 'code'=>'3.1']);
            exit;
        }

        $rec = Resource::orderBy('updated_at', 'DESC')
                         ->leftJoin('config as a', 'resources.type', '=', 'a.id')
                         ->leftJoin('config as b', 'resources.unit', '=', 'b.id')
                         ->leftJoin('members', 'resources.createBy', '=', 'members.id')
                         ->select('resources.*', 'a.name as typeName', 'b.name as unitName', 'members.name as createByName')
                         ->find($id);

        return view('resource.resource_form', ['act'=>'信息修改','rec'=>$rec]);
    }

    /**
        * Update the specified resource in storage.
        *
        * @param  \Illuminate\Http\Request  $request
        * @param  int  $id
        * @return \Illuminate\Http\Response
        */
    public function update(Requests\Resource\ResourceStoreRequest $request, $id)
    {
        // 身份验证
        $arr = ['department'=>'=资源部'];
        $a = new Auth;
        if(!$a->auth($arr)){
            return view('40x',['color'=>'warning', 'type'=>'3', 'code'=>'3.1']);
            exit;
        }

        $rec = Resource::find($id);
        $update = $request->all();
        unset($update['_token']);
        Resource::where('id', $id)->update($update);
        $this->updateResourceState($id);

        //日志
        Logie::add(['notice', '修改资源信息: '.$update['name'].'/'.$update['model']]);

        //微信消息
        $user = Session::get('name');
        $state = $this->getResourceState($id);
        $rec_r = Resource::leftJoin('config as a', 'resources.unit', '=', 'a.id')
                        ->select('resources.*', 'a.name as unitName')
                        ->find($id);
        $resource_name = $rec_r->name;
        $resource_model = $rec_r->model;
        $resource_unit = $rec_r->unitName;
        $resource_remain = floatval($rec_r->remain);
        $resource_notice = floatval($rec_r->notice);
        $resource_alert = floatval($rec_r->alert);

        $body_1 = $user.' 修改: '
                        ."\n".'名称: '.$rec['name'].'->'.$update['name']
                        ."\n".'型号: '.$rec['model'].'->'.$update['model'];
        if($update['notice'] != 0 && $update['alert'] != 0){
            $body_2 = "\n".'提醒值('.$resource_unit.'): '.floatval($rec['notice']).'->'.$update['notice']
                    ."\n".'报警值('.$resource_unit.'): '.floatval($rec['alert']).'->'.$update['alert'];
        }else{
            $body_2 = '';
        }
        if ($state === 0) {
            $body_3 = "\n".'库存为空';
        }elseif ($state === 1) {
            $body_3 = "\n".'进货报警: 库存为 '.$resource_remain;
        }elseif ($state === 2) {
            $body_3 = "\n".'进货提醒: 库存为 '.$resource_remain;
        }else {
            $body_3 = '';
        }
        $body = '[资源修改] '.$body_1.$body_2.$body_3;

        $s = new Select;
        $w = new WeChatAPI;
        $h = new Helper;

        $array = [
              'user'       => '15',
              // 'department' => '资源部',
              //'seek'       => '>=:经理@资源部',
              //'self'       => 'own',
            ];
        
        $url = 'http://'.$h->custom('url').'/resource/show/'.$id;
        $picurl = 'http://'.$h->custom('url').'/custom/image/news/resource.png';
        $arr_news = [['title'=>'资源','description'=>$body,'url'=>$url,'picurl'=>$picurl]];
        
        $w->safe = 0;
        $w->sendNews($s->select($array), $arr_news);

        return redirect('/resource/show/'.$id);
    }

    /**
     * 资源出
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function out($id)
    {
        $remain = $this->getRemain($id);

        $rec = Resource::leftJoin('config', 'resources.unit', '=', 'config.id')
                        ->select('resources.*', 'config.name as unitName')
                        ->find($id);
        // $name = $rec->name;
        // $unit = $rec->unitName;
        // $rec = array('id'=>$id, 'name' => $name, 'unit'=>$unit);
        return view('resource.resource_record_in_out', ['rec'=>$rec, 'remain'=> $remain, 'act'=>'出库']);
    }

    /**
     * 资源出保存
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function outStore(Requests\Resource\ResourceOutRequest $request)
    {
        $input = $request->all();
        $input['out_or_in'] = 0;
        $input['to'] = Session::get('id');
        $amount = $request->amount;
        $type = $request->type;

        ResourceRecord::create($input);

        $id = $input['resource'];
        $this->updateResourceRemain($id);
        $this->updateResourceState($id);
        $this->messageSend($id,$amount,$type);

        //日志
        $rec = Resource::find($id);
        Logie::add(['notice', '资源出库: '.$rec['name'].'/'.$rec['model']]);

        return redirect('/resource/show/'.$id);
    }

    /**
     * 资源进
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function in($id)
    {
        // 身份验证
        $arr = ['department'=>'=资源部'];
        $a = new Auth;
        if(!$a->auth($arr)){
            return view('40x',['color'=>'warning', 'type'=>'3', 'code'=>'3.1']);
            exit;
        }

        $remain = $this->getRemain($id);

        $rec = Resource::leftJoin('config', 'resources.unit', '=', 'config.id')
                        ->select('resources.*', 'config.name as unitName')
                        ->find($id);
        // $name = $rec->name;
        // $unit = $rec->unitName;
        // $rec = array('id'=>$id, 'name' => $name, 'unit'=>$unit);
        return view('resource.resource_record_in_out', ['rec'=>$rec, 'remain'=>$remain, 'act'=>'入库', 'i'=>'in']);
    }

    /**
     * 资源进保存
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function inStore(Requests\Resource\ResourceInRequest $request)
    {
        $input = $request->all();
        $input['out_or_in'] = 1;
        $input['to'] = Session::get('id');
        $input['from'] = 0;
        $amount = $request->amount;
        $type = $request->type;

        ResourceRecord::create($input);

        $id = $input['resource'];
        $this->updateResourceRemain($id);
        $this->updateResourceState($id);
        $this->messageSend($id,$amount,$type);

        //日志
        $rec = Resource::find($id);
        Logie::add(['notice', '资源入库: '.$rec['name'].'/'.$rec['model']]);

        return redirect('/resource/show/'.$id);
    }

    /**
     * 获取资源列表
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function getList($id)
    // {
    //     $remain = $this->getRemain($id);
    //     $resource  = Resource::leftJoin('config as a', 'resources.type', '=', 'a.id')
    //                     ->leftJoin('config as b', 'resources.unit', '=', 'b.id')
    //                     ->leftJoin('members', 'resources.createBy', '=', 'members.id')
    //                     ->select('resources.*', 'a.name as typeName', 'b.name as unitName', 'members.name as createByName')
    //                     ->find($id);

    //     $resource_records = ResourceRecord::where('resource', $id)
    //                           ->leftJoin('members', 'resource_records.to', '=', 'members.id')
    //                           ->leftJoin('config as a', 'resource_records.type', '=', 'a.id')
    //                           ->leftJoin('config as b', 'resource_records.for', '=', 'b.id')
    //                           ->leftJoin('resources', 'resource_records.resource', '=', 'resources.id')
    //                           ->select('resource_records.*', 'members.name as memberName', 'a.name as typeName', 'b.name as forName', 'resources.name as resourceName')
    //                           ->orderBy('created_at', 'DESC')
    //                           ->get();

    //     if(!count($resource_records)) $resource_records = 0;

    //     return view('resource.resource_list', ['resource'=>$resource, 'resource_records'=>$resource_records, 'remain'=>$remain, 'id' => $id]);

    // }


    /**
     * 获取资源库存量
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getRemain($id)
    {
        $in = ResourceRecord::where('resource', $id)
                              ->where('out_or_in', 1)
                              ->sum('amount');

         $out = ResourceRecord::where('resource', $id)
                              ->where('out_or_in', 0)
                              ->sum('amount');
         $remain = floatval($in - $out);

         return $remain;

    }


    /**
     * 更新主表-resource存量
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateResourceRemain($id)
    {
        $remain = $this->getRemain($id);
        Resource::find($id)->update(['remain'=>$remain]);
    }

    /**
     * 计算最新资源状态
     *
     */
    public function getResourceState($id)
    {
        $remain = $this->getRemain($id);
        $rec = Resource::find($id);
        $notice = $rec->notice;
        $alert = $rec->alert;

        if($notice != 0 && $alert != 0){
            if($remain<=0) $state = 0;
            elseif($remain<=$alert && $remain>0) $state = 1;
            elseif($remain<=$notice && $remain>$alert) $state = 2;
            else $state = 3;
        }else{
            $state = 4;
        }

        return $state;
    }

    /**
     * 更新资源状态
     *
     */
    public function updateResourceState($id)
    {
        $state = $this->getResourceState($id);
        Resource::find($id)->update(['state'=>$state]);
    }

    /**
     * 删除资源
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteNote($id)
    {
        // 身份验证
        $arr = ['department'=>'=资源部'];
        $a = new Auth;
        if(!$a->auth($arr)){
            return view('40x',['color'=>'warning', 'type'=>'3', 'code'=>'3.1']);
            exit;
        }

        $abort = '/resource/show/'.$id;
        $delete = '/resource/delete_do/'.$id;

        $arr = ['color'=>'danger', 'type'=>'4','code'=>'4.2', 'btn'=>'放弃', 'link'=>$abort, 'btn1'=>'确定删除', 'link1'=>$delete];
        return view('note',$arr);
    }

    /**
     * 删除资源
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        // 身份验证
        $arr = ['department'=>'=资源部'];
        $a = new Auth;
        if(!$a->auth($arr)){
            return view('40x',['color'=>'warning', 'type'=>'3', 'code'=>'3.1']);
            exit;
        }

        $h = new Helper;

        // 检查存在: ['table'=>'list1|list2|list3', 'table1'=>'list']
        $t =['resource_records'=>'resource']; 
        $rec = Resource::find($id);

        if($a->isRoot() || $a->isAdmin()){  

            if(!$h->exsitsIn($t, $id)){
                Resource::find($id)->delete();
            }
            else{
                Resource::find($id)->update(['show'=>1]);
            }

        }else{
            Resource::find($id)->update(['show'=>1]);
        }

        //日志
        Logie::add(['danger', '删除资源: '.$rec['name'].'/'.$rec['model']]);

        //微信消息
        $user = Session::get('name');

        if($rec['model'] != ''){
            $body = '[资源删除] '.$user.' 删除: '.$rec['name'].'('.$rec['model'].')';
        }else{
            $body = '[资源删除] '.$user.' 删除: '.$rec['name'];
        }

        $s = new Select;
        $w = new WeChatAPI;

        $array = [
              'user'       => '15',
              // 'department' => '资源部',
              //'seek'       => '>=:经理@资源部',
              //'self'       => 'own',
            ];

        // $picurl = 'http://'.$h->custom('url').'/custom/image/news/resource.png';
        // $arr_news = [['title'=>'资源','description'=>$body,'picurl'=>$picurl]];
        
        // $w->safe = 0;
        $w->sendText($s->select($array), $body);

        $arr = ['color'=>'success', 'type'=>'5','code'=>'5.1', 'btn'=>'资源管理', 'link'=>'/resource'];
        return view('note',$arr);

    }

    /**
    * 图片上传表单
    *
    * @param null
    *
    * @return view
    */
    public function image($id)
    {
        return view('upload_image', ['path'=>'/resource/image/store', 'name'=>'资源管理', 'link'=>'/resource', 'resId'=>$id]);
    }

    /**
    * 图片上传处理
    *
    * @param base64
    *
    * @return filse saved view
    */
    public function imageStore(Request $request, $id=0)
    {
        $input = $request->all();
        $base64 = $input['base64'];
        $resId = $input['resId'];
        $base64_body = substr(strstr($base64,','),1);
        $png= base64_decode($base64_body );

        if($id === 0) $id = $resId;
        $target = Resource::find($id);

        $png_name = $target->id.'-'.time().'.png';
        $base_path_img =  base_path().'/public/upload/resource/';
        $path = $base_path_img.$png_name;

        file_put_contents($path, $png);

        if($target->img != '' && $target->img != null) unlink($base_path_img.$target->img);

        $target->update(['img'=>$png_name]);

        //日志
        Logie::add(['info', '图片修改: '.$target->name.'/'.$target->model]);

        $arr = ['color'=>'success', 'type'=>'5','code'=>'5.1', 'btn'=>'看看效果', 'link'=>'/resource/show/'.$id];
        return view('note',$arr);

    }

    /**
     * 
     *
     * @param  
     * @return 
     */
    public function messageSend($id,$amount,$type)
    {
        $s = new Select;
        $w = new WeChatAPI;
        $h = new Helper;

        $state = $this->getResourceState($id);

        $user_name = Session::get('name');
        $cfg = FormConfig::find($type);
        $type = $cfg->name;

        $rec_r = Resource::leftJoin('config as a', 'resources.unit', '=', 'a.id')
                        ->select('resources.*', 'a.name as unitName')
                        ->find($id);
        $resource_name = $rec_r->name;
        $resource_model = $rec_r->model;
        $resource_unit = $rec_r->unitName;
        $resource_remain = floatval($rec_r->remain);
        $resource_notice = floatval($rec_r->notice);
        $resource_alert = floatval($rec_r->alert);

        if ($resource_model != ''){
            $body_1 = $resource_name.'('.$resource_model.') '.$type.' '.$amount.' '.$resource_unit.' -- '.$user_name.';';
        }else{
            $body_1 = $resource_name.' '.$type.' '.$amount.' '.$resource_unit.' -- '.$user_name.';';
        }
        if ($state === 0) {
            $body_3 = '[库存报警]';
            $body_2 = "\n".'库存为空';
        }elseif ($state === 1) {
            $body_3 = '[库存报警]';
            $body_2 = "\n".'进货报警: 库存为 '.$resource_remain.' '.$resource_unit.'  '.'报警值为 '.$resource_alert.' '.$resource_unit;
        }elseif ($state === 2) {
            $body_3 = '[库存提醒]';
            $body_2 = "\n".'进货提醒: 库存为 '.$resource_remain.' '.$resource_unit.'  '.'提醒值为 '.$resource_notice.' '.$resource_unit;
        }else {
            $body_3 = '';
            $body_2 = '';
        }
        $body = $body_3.$body_1.$body_2;

        if ($rec_r->type == 4 || $rec_r->type == 5) {
            $array = [
              'user'       => '15',
              // 'department' => '资源部',
              //'seek'       => '>=:经理@资源部',
              'self'       => 'own',
            ];
        } else {
            $array = [
              // 'user'       => '8',
              'department' => '资源部',
              //'seek'       => '>=:经理@资源部',
              'self'       => 'own',
            ];
        }

        $url = 'http://'.$h->custom('url').'/resource/show/'.$id;
        $picurl = 'http://'.$h->custom('url').'/custom/image/news/resource.png';
        $arr_news = [['title'=>'资源','description'=>$body,'url'=>$url,'picurl'=>$picurl]];
        
        $w->safe = 0;
        $w->sendNews($s->select($array), $arr_news);
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
