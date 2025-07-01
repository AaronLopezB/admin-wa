<?php

namespace App\Http\Controllers\DashBoard;

use App\Http\Controllers\Controller;
use App\Models\Reservations;
use Illuminate\Http\Request;

class DashBoardController extends Controller
{
    public function index()
    {
        // $grafic = $this->getGrafics();
        // dd($grafic);
        return view('dashboard.index');
    }
}
