<?php

namespace App\Http\Controllers\DashBoard;

use App\Http\Controllers\Controller;
use App\Models\Reservations;
use Illuminate\Http\Request;

class DashBoardController extends Controller
{
    public function index()
    {
        $grafic = $this->getGrafics();
        // dd($grafic);
        return view('dashboard.index', compact('grafic'));
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
}
