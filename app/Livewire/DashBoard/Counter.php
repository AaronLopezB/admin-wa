<?php

namespace App\Livewire\DashBoard;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Reservations;
use Livewire\Attributes\Lazy;

#[Lazy]
class Counter extends Component
{



    public function placeholder()
    {
        return view('livewire.placeholder.load-component');
    }

    public function render()
    {
        $grafic = $this->getGrafics();
        // dd($grafic);
        return view('livewire.dash-board.counter', compact('grafic'));
    }

    protected function getGrafics()
    {
        $query = Reservations::query();
        $day = (clone $query)
            ->selectRaw("COUNT(*) as count, COALESCE(SUM(total), 0) AS total")
            ->whereRaw("DATE(created) = '" . now()->format('Y-m-d') . "'")
            ->first()
            ->toArray();

        $yesterday = (clone $query)
            ->selectRaw("COUNT(*) as count, COALESCE(SUM(total), 0) AS total")
            ->whereRaw("DATE(created) = '" . now()->subDay()->format('Y-m-d') . "'")
            ->first()
            ->toArray();

        $week = (clone $query)
            ->selectRaw("COUNT(*) as count, COALESCE(SUM(total), 0) AS total")
            ->whereBetween("created", [
                now()->startOfWeek()->format('Y-m-d'),
                now()->endOfWeek()->format('Y-m-d')
            ])
            ->first()
            ->toArray();

        $calncel = (clone $query)
            ->selectRaw("COUNT(*) as count, COALESCE(SUM(total), 0) AS total")
            ->whereBetween("created", [
                now()->startOfMonth()->format('Y-m-d'),
                now()->endOfMonth()->format('Y-m-d')
            ])
            ->where('estatus', '=', 2)
            ->first()
            ->toArray();
        return [
            'day' => $day,
            'yesterday' => $yesterday,
            'week' => $week,
            'cancel' => $calncel
        ];
    }

    #[On('showSalesNow')]
    public function now()
    {
        $data = Reservations::with('carros')
            ->whereRaw("DATE(created) = '" . now()->format('Y-m-d') . "'")
            ->orderBy('id', 'DESC')
            // ->paginate(10, pageName: 'pageReservations');
            ->get();
        $this->dispatch('showResNow', data: $data);
        // dd('sales now', $data);
    }
}
