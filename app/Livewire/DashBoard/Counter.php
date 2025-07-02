<?php

namespace App\Livewire\DashBoard;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Reservations;
use Maatwebsite\Excel\Facades\Excel;
use Livewire\Attributes\Lazy;
use App\Exports\ResertvationsExport;

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

    #[On('showSalesYesterday')]
    public function yesterday()
    {
        $data = Reservations::with('carros')
            ->whereRaw("DATE(created) = '" . now()->subDay()->format('Y-m-d') . "'")
            ->orderBy('id', 'DESC')
            // ->paginate(10, pageName: 'pageReservations');
            ->get();
        $this->dispatch('showResYesterday', data: $data);
    }

    #[On('showSalesWeek')]
    public function week()
    {
        $data = Reservations::with('carros')
            ->whereBetween("created", [
                now()->startOfWeek()->format('Y-m-d'),
                now()->endOfWeek()->format('Y-m-d')
            ])
            ->orderBy('id', 'DESC')
            // ->paginate(10, pageName: 'pageReservations');
            ->get();
        $this->dispatch('showResWeek', data: $data);
    }

    public function download($time)
    {

        // return response()->streamDownload(function () use ($time) {
        //     $path = storage_path('app/excel');
        //     $now = now()->format('Ymd_His');

        //     (new ResertvationsExport($time))->store("reservations-{$time}-{$now}.xlsx", 'public');
        // }, "reservations-{$time}.xlsx");

        $now = now()->format('Ymd_His');
        $fileName = "reservations-{$time}-{$now}.xlsx";
        return Excel::download(new ResertvationsExport($time), $fileName);
    }
}
