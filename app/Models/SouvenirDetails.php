<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SouvenirDetails extends Model
{
    protected $table = 'detsouvenir';
    protected $primaryKey = 'id';
    protected $fillable = [
        'idvsouvenir',
        'idsouvenir',
        'cantidad',
        'created',
        'updated'
    ];
    public $timestamps = false;
}
