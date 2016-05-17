<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServerVal extends Model
{
    protected $table = 'server_vals'; 
    protected $fillable = ['var_name', 'var_value', 'expire', 'var_up_time'];
    public $timestamps = false;
}
