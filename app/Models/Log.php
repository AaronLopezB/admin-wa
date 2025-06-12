<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    //
    protected $table = 'logs';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'reservacion_id',
        'movimiento',
        'fecha_movimento',
    ];
    public $timestamps = false;
    protected $casts = [
        'fecha_movimento' => 'datetime',
    ];
    protected $dates = [
        'fecha_movimento',
    ];
    protected $guarded = ['id'];

    protected $hidden = [
        'user_id',
        'reservacion_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function reservation()
    {
        return $this->belongsTo(Reservations::class, 'reservacion_id');
    }
}
