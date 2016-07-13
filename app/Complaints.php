<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Complaints extends Model
{
    protected $fillable = ['user_id', 'type', 'target', 'content', 'image', 'state'];
}