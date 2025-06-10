<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomerCard extends Model
{
    protected $table = 'cardcli';

    protected $primaryKey = 'id';
    protected $fillable = [
        'reservacion_id',
        'nombrecard',
        'cardnumber',
        'mes',
        'año',
        'cvc',
        'status'
    ];

    /**
     * Get the reservation that owns the CustomerCard
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reservation(): HasMany
    {
        return $this->hasMany(Reservations::class, 'reservacion_id', 'id');
    }
}
