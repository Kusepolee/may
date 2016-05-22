<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = ['work_id', 'name', 'department', 'position', 'mobile', 'gender', 'email', 'weixinid', 'avatar_mediaid', 'extattr','qq', 'password', 'state', 'admin', 'show', 'new', 'created_by', 'wechat_cdoe', 'img', 'content'];
}


			
            
