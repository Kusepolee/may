<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\FinanceOuts;
use App\FinanceTrans;
use App\Member;
use App\Department;
use Session;
use Illuminate\Http\Request;
use App\Http\Requests;
use FooWeChat\Authorize\Auth;

class FinanceController extends Controller
{
	protected $seekDpArray;
	protected $seekName;

	/*
	 *财务首页
	 *
	 *
	 */
	public function index()
	{
		$a = new Auth;
		if($a->auth(['admin'=>'no', 'user'=>'2', 'position'=>'>=总监', 'department'=>'>=运营部|资源部'])){
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
							->paginate(30);
			$trans = FinanceTrans::where(function ($query) { 
	                            if ($this->seekName != '' && $this->seekName != null) {
	                                $query->where('finance_trans.tran_to', 'LIKE', '%'.$this->seekName.'%');
	                            }
	                        })
							->orderBy('tran_date', 'desc')
							->leftjoin('members as a', 'finance_trans.tran_from', '=', 'a.id')
							->leftjoin('config', 'finance_trans.tran_type', '=', 'config.id')
							->select('finance_trans.*', 'a.name as fromName', 'config.name as tranType')
							->paginate(30);
			// $members = Member::orderBy('id', 'asc')->get();

			foreach($members as $member){
				$out_user[] = FinanceOuts::where('out_user', $member->name)->sum('out_amount');
				$tran_to[] = FinanceTrans::where('tran_to', $member->name)->sum('tran_amount');
				$tran_from[] = FinanceTrans::where('tran_from', $member->id)->sum('tran_amount');
			}
			// $mbs = Member::where('id', '>', 1)->lists('name');
			for ($i=0; $i < 12; $i++) { 
				$remain[0] = 0;
				$remain[] = floatval($tran_to[$i] - $out_user[$i] - $tran_from[$i]);
			}
			var_dump($members);
			exit;

		}elseif ($a->auth(['admin'=>'no', 'position'=>'=总监'])) {

			$id = Session::get('id');
			$user_dp = Member::find($id)->department;
			$user = Session::get('name');
			$outs = FinanceOuts::where(function ($query) { 
	                            if(count($this->seekDpArray)) $query->whereIn('finance_outs.out_about', $this->seekDpArray);
	                            if ($this->seekName != '' && $this->seekName != null) {
	                                $query->where('finance_outs.out_user', 'LIKE', '%'.$this->seekName.'%');
	                            }
	                        })
							->where('out_about', $user_dp)
							->orderBy('out_date', 'desc')
							->leftjoin('departments', 'finance_outs.out_about', '=', 'departments.id')
							->leftjoin('config', 'finance_outs.out_bill', '=', 'config.id')
							->select('finance_outs.*', 'config.name as outBill', 'departments.name as dpName')
							->paginate(30);				
			$recs = Member::where('department', $user_dp)->get();
			foreach ($recs as $rec) {
				$name = $rec->name;
			}
			$trans = FinanceTrans::where(function ($query) { 
	                            if ($this->seekName != '' && $this->seekName != null) {
	                                $query->where('finance_trans.tran_to', 'LIKE', '%'.$this->seekName.'%');
	                            }
	                        })
							->whereIn('tran_to', [$name, $user])
							->orwhere('tran_from', $id)
							->orderBy('tran_date', 'desc')
							->leftjoin('members as a', 'finance_trans.tran_from', '=', 'a.id')
							->leftjoin('config', 'finance_trans.tran_type', '=', 'config.id')
							->select('finance_trans.*', 'a.name as fromName', 'config.name as tranType')
							->paginate(30);
			$in = FinanceTrans::where('tran_to', $user)->sum('tran_amount');
			$out = FinanceOuts::where('out_user', $user)->sum('out_amount');
			$remain = floatval($in - $out);

		}else{

			$members = Session::get('name');
			$outs = FinanceOuts::where('out_user', $members)
							->orderBy('out_date', 'desc')
							->leftjoin('departments', 'finance_outs.out_about', '=', 'departments.id')
							->leftjoin('config', 'finance_outs.out_bill', '=', 'config.id')
							->select('finance_outs.*', 'config.name as outBill', 'departments.name as dpName')
							->paginate(30);
			$trans = FinanceTrans::where('tran_to', $members)
							->orderBy('tran_date', 'desc')
							->leftjoin('members as a', 'finance_trans.tran_from', '=', 'a.id')
							->leftjoin('config', 'finance_trans.tran_type', '=', 'config.id')
							->select('finance_trans.*', 'a.name as fromName', 'config.name as tranType')
							->paginate(30);
			$in = FinanceTrans::where('tran_to', $members)->sum('tran_amount');
			$out = FinanceOuts::where('out_user', $members)->sum('out_amount');
			$remain = floatval($in - $out);

		}

		$recs = Department::where('id', '>', 1)
					->get();
		if(count($recs)){
			$dp = ['0'=>'不限部门'];
			foreach ($recs as $rec) {
				$dp = array_add($dp, $rec->id, $rec->name);
			}
		}
		
		return view('finance.finance', ['seekName'=>$this->seekName, 'seekDp'=>$this->seekDpArray, 'outs'=>$outs, 'trans'=>$trans, 'Dp'=>$dp, 'remains'=>$remain, 'members'=>$members]);
	}

	/**
	* 支出页面
	*/
	public function out()
	{
		$user = Session::get('name');
		$recs = Department::where('id', '>', 1)
					->get();
		if(count($recs)){
			$dp = [];
			foreach ($recs as $rec) {
				$dp = array_add($dp, $rec->id, $rec->name);
			}
		}

		return view('finance.finance_outs', ['user'=>$user, 'dp'=>$dp]);
	}

	/**
	* 支出信息存入数据库
	*/
	public function outStore(Requests\Finance\FinanceOutRequest $request)
	{
		$input = $request->all();
		
		//var_dump($input);
		FinanceOuts::create($input);

		return redirect('/finance');
	}

	/**
	* 支出页面
	*/
	public function tran()
	{
		$user = Session::get('name');
		$recs = Member::where('id', '>', 1)
					->where('position', '<',8)
					->get();

		if(count($recs)){
			$boss = [];
			foreach ($recs as $rec) {
				$boss = array_add($boss, $rec->id, $rec->name);
			}
		}

		return view('finance.finance_trans', ['user'=>$user, 'boss'=>$boss]);
	}

	/**
	* 支出信息存入数据库
	*/
	public function tranStore(Requests\Finance\FinancetranRequest $request)
	{
		$input = $request->all();
		
		//var_dump($input);
		Financetrans::create($input);

		return redirect('/finance');
	}

	/**
    * 查询
    */
    public function financeSeek(Requests\Finance\FinanceSeekRequest $request)
    {
        $seek = $request->all();

        if ($seek['seekDp'] == 0 && ($seek['seekName'] =='' || $seek['seekName'] == null)) {
            //go on
        }else{

            if($seek['seekDp'] != 0) {
                $seekDp = $seek['seekDp'];
                if(count($seekDp)){
                    $this->seekDpArray = [$seekDp];
                }else{
                    $arr = ['color'=>'info', 'type'=>'6','code'=>'6.1', 'btn'=>'返回资源管理', 'link'=>'/resource'];
                    return view('note',$arr);
                }
            }

            if($seek['seekName'] != '' && $seek['seekName'] != null) $this->seekName= $seek['seekName'];
        }
       
        return $this->index();       
    }

}    