<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IpVisita extends Model
{
    public $timestamps = false;
    public $incrementing = false;
    protected $fillable = ['fecha', 'ip'];
}
