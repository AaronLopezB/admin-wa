<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehiclesPerson extends Model
{
    protected $table = 'people_vehicle';

    protected $primaryKey = 'id';
    protected $fillable = [
        'persons',
        'total'
    ];

    public function reservations()
    {
        return $this->belongsTo(Reservations::class,'id_reserva', 'id');
    }
    public function cars()
    {
        return $this->belongsTo(Cars::class,'id_carro','id');
    }
}
