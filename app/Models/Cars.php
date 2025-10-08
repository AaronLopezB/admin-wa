<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Cars extends Model
{
    protected $table = 'carros';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_tipo',
        'numero',
        'nombre',
        'asientos',
        'descripcion',
        'precio',
        'disponibles',
        'img',
        'img_vehiculo',
        'estatus',
        'orden',
        'identidicador',
        'location',
        'servicio'
    ];

    public function getRouteKeyName()
    {
        return 'identidicador';
    }

    public function setSlugAttribute()
    {
        $this->attributes['identidicador'] = Str::slug($this->nombre);
    }

    public function getStatusNameAttribute()
    {
        return [
            0 => 'Deshabilitado',
            1 => 'Habilitado'
        ][$this->location] ?? 'Habilitado';
    }

    public function getColorStatusAttribute()
    {
        return [
            0 => 'secondary',
            1 => 'success'
        ][$this->location] ?? 'success';
    }

    public static function slugVehicle($identity)
    {
        return Cars::where('identidicador', $identity)->first();
    }

    /**
     * The carros that belong to the Cars
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function carros(): BelongsToMany
    {
        return $this->belongsToMany(Reservations::class, 'carros_reservados', 'id_carro', 'id_reserva')->wherePivot('total_reservas', 'descuento', 'total_cobrar', 'is_delete', 'fecha', 'hora', 'tipo');
    }

    public function persons()
    {
        return $this->hasMany(VehiclesPerson::class, 'id_carro', 'id');
    }
}
