<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function validate_user(Request $request)
    {
        switch (auth()->user()->role_id) {
            case 1:
                return redirect()->route('filament.pages.dashboard');
                break;

            case 3:
                return redirect(route('passenger.fare_matrix'));
                break;
            default:
                # code...
                break;
        }
    }
}
