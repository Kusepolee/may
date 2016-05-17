<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductQuota extends Model
{
    protected $table = 'product_quota'; 
    protected $fillable = ['name', 'resource', 'amount', 'type', 'time', 'work_type', 'createBy'];
}

