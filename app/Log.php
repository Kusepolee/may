<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'log'; 
    protected $fillable = ['member_id', 'name', 'type', 'content', 'way', 'city', 'point', 'ip'];
}