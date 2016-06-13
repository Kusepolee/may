<?php

namespace App\Http\Controllers\Resource;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Resource;
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
                          ->orderBy('updated_at', 'DESC')
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
        
        Resource::create($input);

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

        $update = $request->all();
        unset($update['_token']);
        Resource::where('id', $id)->update($update);
        $this->updateState($id);

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

        ResourceRecord::create($input);

        $id = $input['resource'];
        $this->updateResourceRemain($id);
        $this->updateState($id);

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

        ResourceRecord::create($input);

        $id = $input['resource'];
        $this->updateResourceRemain($id);
        $this->updateState($id);

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
     * 更新资源状态
     *
     */
    public function updateState($id)
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


        $arr = ['color'=>'success', 'type'=>'5','code'=>'5.1', 'btn'=>'看看效果', 'link'=>'/resource/show/'.$id];
        return view('note',$arr);

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
