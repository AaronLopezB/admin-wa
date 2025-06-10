<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservationCode extends Model
{
    protected $table = 'codigo_reserva';

    protected $primaryKey = 'id';
    protected $fillable = [
        'cupon_id',
        'reservacion_id'
    ];
}
