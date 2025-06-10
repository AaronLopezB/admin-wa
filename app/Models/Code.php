<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Code extends Model
{
    protected $table = 'codigo';

    protected $primaryKey = 'id';
    protected $fillable = ['codigo', 'descuento', 'estatus'];

    /**
     * The reserva that belong to the Reservations
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function reserva(): BelongsToMany
    {
        return $this->belongsToMany(Reservations::class, 'codigo_reserva', 'cupon_id', 'reservacion_id')->withPivot('created_at', 'updated_at');
    }
}
