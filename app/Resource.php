<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $fillable = ['createBy', 'name', 'unit', 'notice', 'alert', 'remain', 'type', 'content'];
}
