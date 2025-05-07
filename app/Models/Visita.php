<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visita extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'fecha';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['fecha', 'total'];
}
