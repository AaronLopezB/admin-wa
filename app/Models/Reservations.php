<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Reservations extends Model
{
    protected $table = 'reservaciones';

    protected $primaryKey = 'id';
    protected $fillable = [
        'nombre',
        'apellidos',
        'email',
        'telefono',
        'entrada_paquete',
        'direccion',
        'fecha_reservacion',
        'hora_reservacion',
        'importe',
        'impuesto',
        'total',
        'estatus',
        'tipo_venta',
        'id_calendar',
        'id_tipor',
        'terminos',
        'licensia',
        'location',
        'note',
        'estatusfintour',
        'inf_mail',
        'stripe_id',
        'name_gift',
        'mail_gift',
        'created'
    ];
    public $timestamps = false;

    /**
     * Get the card that owns the Reservations
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function card(): HasOne
    {
        return $this->hasOne(CustomerCard::class, 'reservacion_id');
    }

    /**
     * The carros that belong to the Reservations
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function carros(): BelongsToMany
    {
        return $this->belongsToMany(Cars::class, 'carros_reservados', 'id_reserva', 'id_carro')->withPivot('total_reservas', 'descuento', 'total_cobrar', 'is_delete', 'fecha', 'hora', 'tipo');
    }
    /**
     * The coupon that belong to the Reservations
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function coupon(): BelongsToMany
    {
        return $this->belongsToMany(Code::class, 'codigo_reserva', 'reservacion_id', 'cupon_id')->withPivot('created_at', 'updated_at');
    }

    public function getFullNameAttribute()
    {
        return "$this->nombre $this->apellidos";
    }

    public function getDateFormatAttribute()
    {
        App::setLocale('es');
        Carbon::setLocale('es');
        return $this->fecha_reservacion ? Carbon::parse($this->fecha_reservacion)->translatedFormat('d M, Y') : null;
    }

    public function getTimeFormatAttribute()
    {
        return date('h:i A', strtotime($this->hora_reservacion));
    }

    public function getCreatedFormatAttribute()
    {
        return Carbon::parse($this->created)->translatedFormat('d M, Y');
    }

    public function getStatusFormatAttribute()
    {
        return [
            1 => 'Creada',
            2 => 'Cancelada',
            3 => 'Eliminada',
            8 => 'Regalo'
        ][$this->estatus] ?? 'Creada';
    }

    public function getColorStatusAttribute()
    {
        return [
            1 => 'success',
            2 => 'danger',
            3 => 'danger',
            8 => 'info'
        ][$this->estatus] ?? 'success';
    }

    public function persons()
    {
        return $this->hasMany(VehiclesPerson::class, 'id_reserva', 'id');
    }
}
