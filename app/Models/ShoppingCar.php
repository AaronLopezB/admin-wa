<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShoppingCar extends Model
{
    protected $table = 'shopping_car';
    protected $fillable = [
        'identity',
        'date',
        'hour',
        'person_car',
        'is_gift',
        'available',
        'price',
        'total',
        'totalGuests'
    ];


    public function carros()
    {
        return $this->belongsTo(Cars::class, 'car_id', 'id');
    }
    public function code()
    {
        return $this->belongsTo(Code::class, 'codigo_id', 'id');
    }
}
