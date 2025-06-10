<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Refunds extends Model
{
    protected $table = 'refunds';

    protected $primaryKey = 'id';
    protected $fillable = [
        'nombre',
        'cantidad',
        'reservacion',
        'namecard',
        'numbercard',
        'mes',
        'anio',
        'cvc',
        'firma',
        'date',
        'licencia',
        'zip'
    ];

}
