<?php

namespace Tests\Unit;

use App\Livewire\DashBoard\Dashboard;
use App\Models\Reservations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_customer_validation_error()
    {
        $reservation = Reservations::factory()->create();
        Livewire::test(Dashboard::class)
            ->set('reservation_id', $reservation->id)
            ->set('rnombre', '')
            ->set('rapellidos', '')
            ->set('rtelefono', '123')
            ->set('remail', 'not-an-email')
            ->call('updateCustomer')
            ->assertDispatched('notify', function ($payload) {
                return $payload['type'] === 'error';
            });
    }

    public function test_update_customer_success()
    {
        $reservation = Reservations::factory()->create();
        Livewire::test(Dashboard::class)
            ->set('reservation_id', $reservation->id)
            ->set('rnombre', 'Juan')
            ->set('rapellidos', 'Pérez')
            ->set('rtelefono', '+521234567890')
            ->set('remail', 'juan@example.com')
            ->call('updateCustomer')
            ->assertDispatched('notify', function ($payload) {
                return $payload['type'] === 'success';
            });
        $this->assertDatabaseHas('reservaciones', [
            'id' => $reservation->id,
            'nombre' => 'Juan',
            'apellidos' => 'Pérez',
            'telefono' => '+521234567890',
            'email' => 'juan@example.com',
        ]);
    }
}
