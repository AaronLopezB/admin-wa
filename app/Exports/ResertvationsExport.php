<?php

namespace App\Exports;

use App\Models\Reservations;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
// use Maatwebsite\Excel\Concerns\FromCollection;

class ResertvationsExport implements FromView, ShouldAutoSize, WithTitle
{
    use Exportable;
    protected $time;

    function __construct($time)
    {
        $this->time = $time;
    }

    public function view(): View
    {

        $query = Reservations::with('carros');

        switch ($this->time) {
            case 'now':
                $data = $query->whereDate('created', now()->toDateString())->get();
                break;
            case 'yesterday':
                $data = $query->whereDate('created', now()->subDay()->toDateString())->get();
                break;
            case 'week':
                $data = $query->whereBetween('created', [
                    now()->startOfWeek()->toDateString(),
                    now()->endOfWeek()->toDateString()
                ])->get();
                break;
            default:
                $data = collect();
                break;
        }

        return view('exports.reservation-by-time', [
            'reservations' => $data
        ]);
    }

    public function title(): string
    {
        return 'Reservations to ' . $this->time;
    }
}
