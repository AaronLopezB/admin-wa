<?php

namespace App\Http\Controllers\Vehicles;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VehiclesController extends Controller
{
    public function index()
    {
        return view('vehicles.index');
    }

    public function edit($id)
    {
        return view('vehicles.edit', compact('id'));
    }
}
