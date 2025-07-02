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

    public function search(Request $request)
    {
        $pos = strpos($request->search, ':');
        // dd($pos);
        if ($pos != false) {
            $model = trim(substr($request->search, 0, $pos));
            $word = trim(substr($request->search, $pos + 1));
            dd($model, $word);
        } else {
            $query = Reservations::with('carros')->when($request->search, function ($query) use ($request) {
                $query->where('id', 'like', '%' . $request->search . '%')
                    ->orWhere('nombre', 'like', '%' . $request->search . '%')
                    ->orWhere('apellidos', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('telefono', 'like', '%' . $request->search . '%')
                    ->orWhere('fecha_reservacion', 'like', '%' . $request->search . '%')
                    ->orWhere('hora_reservacion', 'like', '%' . $request->search . '%');
            })
                ->select('id', 'nombre', 'apellidos', 'email', 'telefono', 'fecha_reservacion', 'hora_reservacion', 'created', 'total', 'estatus')
                ->orderBy('id', 'DESC')
                ->get();
            return response()->json($query, 200);
            // dd($query);
        }
        $key = [
            'u', #user
            'r', #reservation
            'p', #products
            'c', #coupons
        ];
        // $model = $key[]


        dd($request->all());
    }
}
