<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function getIndex() {
        if (Auth::user()) {
            return view('app.home');
        }

        return view('app.login');
    }
}
