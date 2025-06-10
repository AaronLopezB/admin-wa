<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registers extends Model
{
    protected $table = 'logs';

    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'nombrecard',
        'reservacion_id',
        'movimiento',
        'fecha_movimento'
    ];
    public $timestamps = false;
}
