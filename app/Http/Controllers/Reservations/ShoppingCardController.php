<?php

namespace App\Http\Controllers\Reservations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShoppingCardController extends Controller
{
    public function index()
    {
        // This method should return the view for the shopping cart
        return view('reservations.index');
    }
}
