<?php

namespace Database\Factories;

use App\Models\Reservations;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationsFactory extends Factory
{
    protected $model = Reservations::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->firstName,
            'apellidos' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'telefono' => $this->faker->phoneNumber,
            'fecha_reservacion' => $this->faker->date(),
            'hora_reservacion' => $this->faker->time('H:i'),
            'total' => $this->faker->randomFloat(2, 100, 1000),
            'estatus' => 1,
            'created' => now(),
        ];
    }
}
