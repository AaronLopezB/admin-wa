<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disputes extends Model
{
    protected $primaryKey = 'id';
    protected $fillable = [
        'nombre',
        'apellidos',
        'telefono',
        'correo',
        'direccion',
        'codigo_postal',
        'tarjeta_id',
        'monto'
    ];
}
