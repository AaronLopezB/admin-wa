<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Establishments extends Model
{
    protected $table = 'establecimientos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nombre',
        'codigo',
        'descuento'
    ];
}
