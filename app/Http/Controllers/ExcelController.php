<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Member;
use App\Resource;
use App\FinanceOuts;
use App\FinanceTrans;
use Excel;
use FooWeChat\Authorize\Auth;
use FooWeChat\Helpers\Helper;
use Illuminate\Http\Request;
use Logie;
use Session;



class ExcelController extends Controller
{
    protected $departmentsArray;
    protected $positionsArray;
    protected $rescTypesArray;
    protected $seekDpArray;
    protected $seekName;
    protected $key;
    /**
     * 获取用户信息: 员工
     *
     * @return excel download
     */
    public function getMembers(Request $request)
    {
        $arr = ['admin'=>'no', 'position'=>'>=总监', 'department' => '>=运营部'];

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

        $data_array = [['编号', '姓名', '性别', '部门', '职位', '手机', '邮件', '微信号', 'QQ号', '备注']];

        if(count($recs)){
            foreach ($recs as $rec) {
                $tmp_array = [];
                $tmp_array[] = $rec->work_id;
                $tmp_array[] = $rec->name;
                $tmp_array[] = $rec->genderName;
                $tmp_array[] = $rec->departmentName;
                $tmp_array[] = $rec->positionName;
                $tmp_array[] = '#'.$rec->mobile;
                $tmp_array[] = $rec->email;
                $tmp_array[] = $rec->weixinid;
                $tmp_array[] = '#'.$rec->qq;
                $tmp_array[] = $rec->content;

                $data_array[] = $tmp_array;
            }
        }

        //日志
        Logie::add(['danger', '下载用户列表为excel']);


        $name = date("Y-m-d-H-i",time()).'_users';

        Excel::create($name,function($excel) use ($data_array){
            $excel->sheet('员工', function($sheet) use ($data_array){
                $sheet->setAutoSize(true);
                $sheet->freezeFirstRow();
                $sheet->rows($data_array);
            });
        })->export('xls');
    }

    /**
     * 获取资源信息
     *
     * @return excel download
     */
    public function getResources(Request $request)
    {
        $arr = ['admin'=>'no', 'position'=>'>=经理', 'department' => '>=资源部|生产部'];

        $a = new Auth;
        if(!$a->auth($arr)){
            return view('40x',['color'=>'warning', 'type'=>'3', 'code'=>'3.1']);
        }
        // ^ 身份验证

        $seek_string = $request->seek_string;
        $seek_array = explode('-',$seek_string);

        $seek_array[0] != '_not' ? $this->rescTypesArray = explode("|", $seek_array[0]) : $this->rescTypesArray = [];   
        $seek_array[1] != '_not' ? $this->key = $seek_array[1] : $this->key = '';    

        

        $recs = Resource::where(function ($query) { 
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
                          ->get();

        $data_array = [['编号', '名称', '型号', '库存', '单位', '类型', '提醒值', '报警值', '创建人', '备注']];

        if(count($recs)){
            foreach ($recs as $rec) {
                $tmp_array = [];
                $tmp_array[] = $rec->id;
                $tmp_array[] = $rec->name;
                $tmp_array[] = $rec->model;
                $tmp_array[] = floatval($rec->remain);
                $tmp_array[] = $rec->unitName;
                $tmp_array[] = $rec->typeName;
                $tmp_array[] = floatval($rec->notice);
                $tmp_array[] = floatval($rec->alert);
                $tmp_array[] = $rec->createByName;
                $tmp_array[] = $rec->content;

                $data_array[] = $tmp_array;
            }
        }

        //日志
        Logie::add(['danger', '下载资源列表为excel']);

        $name = date("Y-m-d-H-i",time()).'_resources';

        Excel::create($name,function($excel) use ($data_array){
            $excel->sheet('资源', function($sheet) use ($data_array){
                $sheet->setAutoSize(true);
                $sheet->freezeFirstRow();
                $sheet->rows($data_array);
            });
        })->export('xls');
    }

    /**
     * 获取财务信息
     *
     * @return excel download
     */
    public function getFinance(Request $request)
    {
        $arr = ['admin'=>'no', 'user'=>'2', 'position'=>'>=总监', 'department' => '>=运营部|资源部'];

        $a = new Auth;
        if(!$a->auth($arr)){
            return view('40x',['color'=>'warning', 'type'=>'3', 'code'=>'3.1']);
        }
        // ^ 身份验证

        $seek_string = $request->seek_string;
        $seek_array = explode('-',$seek_string);

        $seek_array[0] != '_not' ? $this->seekDpArray = explode("|", $seek_array[0]) : $this->seekDpArray = [];   
        $seek_array[1] != '_not' ? $this->seekName = $seek_array[1] : $this->seekName = '';    

        $outs = FinanceOuts::where(function ($query) { 
                            if(count($this->seekDpArray)) $query->whereIn('finance_outs.out_about', $this->seekDpArray);
                            if ($this->seekName != '' && $this->seekName != null) {
                                $query->where('finance_outs.out_user', 'LIKE', '%'.$this->seekName.'%');
                            }
                        })
                        ->orderBy('out_date', 'desc')
                        ->leftjoin('departments', 'finance_outs.out_about', '=', 'departments.id')
                        ->leftjoin('config', 'finance_outs.out_bill', '=', 'config.id')
                        ->select('finance_outs.*', 'config.name as outBill', 'departments.name as dpName')
                        ->get();

        $trans = FinanceTrans::where(function ($query) { 
                            if ($this->seekName != '' && $this->seekName != null) {
                                $query->where('finance_trans.tran_to', 'LIKE', '%'.$this->seekName.'%');
                            }
                        })
                        ->orderBy('tran_date', 'desc')
                        ->leftjoin('members as a', 'finance_trans.tran_from', '=', 'a.id')
                        ->leftjoin('config', 'finance_trans.tran_type', '=', 'config.id')
                        ->select('finance_trans.*', 'a.name as fromName', 'config.name as tranType')
                        ->get();

        $data_array1 = [['编号', '经手人', '金额', '日期', '支出项', '单据', '相关业务']];       

        if(count($outs)){
            foreach ($outs as $out) {
                $tmp_array = [];
                $tmp_array[] = $out->out_id;
                $tmp_array[] = $out->out_user;
                $tmp_array[] = $out->out_amount;
                $tmp_array[] = $out->out_date;
                $tmp_array[] = $out->out_item;
                $tmp_array[] = $out->outBill;
                $tmp_array[] = $out->dpName;

                $data_array1[] = $tmp_array;
            }
        }

        $data_array2 = [['编号', '金额', '流向', '日期', '方式', '内容']];

        if(count($trans)){
            foreach ($trans as $tran) {
                $tmp_array = [];
                $tmp_array[] = $tran->tran_id;
                $tmp_array[] = $tran->tran_amount;
                $tmp_array[] = $tran->fromName.' --> '.$tran->tran_to;
                $tmp_array[] = $tran->tran_date;
                $tmp_array[] = $tran->tranType;
                $tmp_array[] = $tran->tran_item;

                $data_array2[] = $tmp_array;
            }
        }

        //日志
        Logie::add(['danger', '下载财务信息为excel']);

        $name = date("Y-m-d-H-i",time()).'_finance';

        Excel::create($name,function($excel) use ($data_array1,$data_array2){
            $excel->sheet('财务支出', function($sheet) use ($data_array1){
                $sheet->setAutoSize(true);
                $sheet->freezeFirstRow();
                $sheet->rows($data_array1);
            })->sheet('财务流向', function($sheet) use ($data_array2){
                $sheet->setAutoSize(true);
                $sheet->freezeFirstRow();
                $sheet->rows($data_array2);
            });
        })->export('xls');
    }

    /**
     * test
     *
     * @return \Illuminate\Http\Response
     */
    public function test()
    {
        $b = 0;
        $recs = Member::where(function ($query) {
                $query->where('work_id', '<', 30);
                if(Session::has('id')){
                    $query->where('name', '=', '陆鹏');
                }
            })->get();


        foreach ($recs as $c) {
            echo $c->name;
        }
    }

}

