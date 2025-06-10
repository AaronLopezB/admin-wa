<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemorialList extends Model
{
    protected $table = 'listasouvenirs';
    protected $primaryKey = 'idSouvenir';
    protected $fillable = [
        'nombre',
        'costo',
        'fechaCreada'
    ];
    public $timestamps = false;
}
