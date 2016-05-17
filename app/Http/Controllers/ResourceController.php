<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Resource;
use App\ResourceRecord;
use DB;
use Session;

class ResourceController extends Controller
{
    /**
     * 资源管理
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $outs = Resource::orderBy('updated_at', 'DESC')
                         ->leftJoin('config as a', 'resources.type', '=', 'a.id')
                         ->leftJoin('config as b', 'resources.unit', '=', 'b.id')
                         ->leftJoin('members', 'resources.createBy', '=', 'members.id')
                         ->select('resources.*', 'a.name as typeName', 'b.name as unitName', 'members.name as createByName')
                         ->paginate(30);

        if(!count($outs)) $outs = 0;
        return view('resource', ['outs'=>$outs]);
    }

    /**
     * 登记资源
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('resource_form', ['act'=>'资源登记']);
    }

    /**
     * 保存登记资源
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\ResourceStoreReqest $request)
    {
        $input = $request->all();
        $input['createBy'] = intval(Session::get('id'));

        Resource::create($input);

        return redirect('/resource');
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

        $outs = Resource::leftJoin('config', 'resources.unit', '=', 'config.id')
                        ->select('resources.name', 'config.name as unitName')
                        ->find($id);
        $name = $outs->name;
        $unit = $outs->unitName;
        $outs = array('id'=>$id, 'name' => $name, 'unit'=>$unit);
        return view('resource_record_out', ['outs'=>$outs, 'remain'=> $remain]);
    }

    /**
     * 资源出保存
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function outStore(Requests\ResourceOutRequest $request)
    {
        $input = $request->all();
        $input['out_or_in'] = 0;
        $input['to'] = Session::get('id');

        ResourceRecord::create($input);

        $id = $input['resource'];
        $this->updateResourceRemain($id);

        return redirect('/resource/list/'.$id);
    }

    /**
     * 资源进
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function in($id)
    {
        $remain = $this->getRemain($id);

        $outs = Resource::leftJoin('config', 'resources.unit', '=', 'config.id')
                        ->select('resources.name', 'config.name as unitName')
                        ->find($id);
        $name = $outs->name;
        $unit = $outs->unitName;
        $outs = array('id'=>$id, 'name' => $name, 'unit'=>$unit);
        return view('resource_record_in', ['outs'=>$outs, 'remain'=>$remain]);
    }

    /**
     * 资源进保存
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function inStore(Requests\ResourceInRequest $request)
    {
        $input = $request->all();
        $input['out_or_in'] = 1;
        $input['to'] = Session::get('id');
        $input['from'] = 0;

        ResourceRecord::create($input);

        $id = $input['resource'];
        $this->updateResourceRemain($id);

        return redirect('/resource/list/'.$id);
    }

    /**
     * 获取资源列表
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getList($id)
    {
        $remain = $this->getRemain($id);
        $resource  = Resource::leftJoin('config as a', 'resources.type', '=', 'a.id')
                        ->leftJoin('config as b', 'resources.unit', '=', 'b.id')
                        ->leftJoin('members', 'resources.createBy', '=', 'members.id')
                        ->select('resources.*', 'a.name as typeName', 'b.name as unitName', 'members.name as createByName')
                        ->find($id);

        $resource_records = ResourceRecord::where('resource', $id)
                              ->leftJoin('members', 'resource_records.to', '=', 'members.id')
                              ->leftJoin('config as a', 'resource_records.type', '=', 'a.id')
                              ->leftJoin('config as b', 'resource_records.for', '=', 'b.id')
                              ->leftJoin('resources', 'resource_records.resource', '=', 'resources.id')
                              ->select('resource_records.*', 'members.name as memberName', 'a.name as typeName', 'b.name as forName', 'resources.name as resourceName')
                              ->orderBy('created_at', 'DESC')
                              ->get();

        if(!count($resource_records)) $resource_records = 0;

        return view('resource_list', ['resource'=>$resource, 'resource_records'=>$resource_records, 'remain'=>$remain, 'id' => $id]);

    }


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
