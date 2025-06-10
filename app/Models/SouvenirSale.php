<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SouvenirSale extends Model
{
    protected $table = 'ventasouvenir';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nombre',
        'apellido',
        'correo',
        'telefono',
        'direccion',
        'colonia',
        'tipocasa',
        'ciudad',
        'estado',
        'cp',
        'pais',
        'estatus'
    ];
    public $timestamps = false;
}
