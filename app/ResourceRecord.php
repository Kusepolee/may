<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResourceRecord extends Model
{
    protected $table = 'resource_records'; 
    protected $fillable = ['resource', 'type', 'amount', 'out_or_in', 'from', 'to', 'for', 'content', 'token'];
}
