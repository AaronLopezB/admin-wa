<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservedCars extends Model
{
    protected $table = 'carros_reservados';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_reserva',
        'id_carro',
        'total_reservas',
        'descuento',
        'total_cobrar',
        'tipo'
    ];
    public $timestamps = false;
}
