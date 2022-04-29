<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PassengerPagesController extends Controller
{
    public function fare_matrix(Request $request)
    {
        return view('welcome');
    }
}
