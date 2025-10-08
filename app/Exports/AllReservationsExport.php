<?php

namespace App\Exports;

use App\Models\Reservations;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AllReservationsExport implements FromView, ShouldAutoSize, WithTitle
{
    use Exportable;

    protected $date;
    protected $status;

    function __construct($date, $status)
    {
        $this->date = $date;
        $this->status = $status;
    }

    public function view(): View
    {
        $query = Reservations::with('carros')
            ->whereBetween('fecha_reservacion', [$this->date[0], $this->date[1]]);
        switch ($this->status) {
            case 'todos':
                $data = $query->whereIn('estatus', [1, 2, 3, 8]);
                break;
            case 'comprados':
                $data = $query->where('estatus', 1);
                break;
            case 'regalo':
                $data = $query->where('estatus', 8);
                break;
            case 'cancelado':
                $data = $query->whereIn('estatus', [2, 3]);
                break;

            default:
                $data = $query->whereIn('estatus', [1, 2, 3, 8]);
                break;
        }
        // dd(, $this->date, $this->status);
        return view('exports.reservation-by-time', [
            'reservations' => $data->orderBy('fecha_reservacion', 'DESC')->get()
        ]);
    }

    public function title(): string
    {
        return 'Reservations to ';
    }
}
