<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FinanceTrans extends Model
{
	protected $table = 'finance_trans';
	protected $fillable = ['tran_id', 'tran_amount', 'tran_item', 'tran_date', 'tran_from', 'tran_to', 'tran_type'];
	public $timestamps = false;
}