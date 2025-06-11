<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
{
    protected $table = 'notes';

    protected $fillable = [
        'note',
        'id_reservation',
        'id_user',
        'created_at',
        'updated_at'
    ];

    public $timestamps = true;

    protected function createdAt(): Attribute
    {
        return new Attribute(
            get: fn($value) => $value ? Carbon::parse($value)->format('d M, Y H:i') : null,
        );
    }

    /**
     * Get the reservation that owns the note.
     */
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservations::class, 'id_reservation');
    }

    /**
     * Get the user that owns the Note
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
