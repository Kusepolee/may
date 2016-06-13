<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $fillable = ['createBy', 'name', 'model', 'unit', 'notice', 'alert', 'remain', 'type', 'content', 'level', 'state', 'show', 'img'];
}
