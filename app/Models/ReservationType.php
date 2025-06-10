<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservationType extends Model
{
    protected $table = 'tipo_reservacion';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nombre'
    ];
    public $timestamps = false;
}
