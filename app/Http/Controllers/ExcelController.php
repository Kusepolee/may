<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Member;
use Excel;
use FooWeChat\Authorize\Auth;
use FooWeChat\Helpers\Helper;
use Illuminate\Http\Request;
use Session;



class ExcelController extends Controller
{
    /**
     * 获取用户信息: 员工
     *
     * @return excel download
     */
    public function getMembers()
    {
        $arr = ['admin'=>'no', 'position'=>'>=副总经理'];

        $a = new Auth;
        if(!$a->auth($arr)){
            return view('40x',['color'=>'warning', 'type'=>'3', 'code'=>'3.1']);
        }
        // ^ 身份验证

        $recs = Member::where('members.id', '>', 1)
                ->where('members.show', 0)
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
                $tmp_array[] = $rec->mobile;
                $tmp_array[] = $rec->email;
                $tmp_array[] = $rec->weixinid;
                $tmp_array[] = $rec->qq;
                $tmp_array[] = $rec->content;

                $data_array[] = $tmp_array;
            }
        }

        Excel::create('users',function($excel) use ($data_array){
            $excel->sheet('员工', function($sheet) use ($data_array){
                $sheet->setAutoSize(true);
                $sheet->freezeFirstRow();
                $sheet->rows($data_array);
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

