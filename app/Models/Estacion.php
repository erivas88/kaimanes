<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estacion extends Model
{
    use HasFactory;
    protected $table = 'Estaciones'; // Nombre de la tabla
    protected $primaryKey = 'id_estacion'; // Clave primaria
    public $timestamps = false; // Si no tienes created_at y updated_at
}
