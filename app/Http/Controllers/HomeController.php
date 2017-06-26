<?php namespace App\Http\Controllers;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Routing\Controller;

class HomeController extends Controller {

    public function getIndex() {
        if (Sentinel::getUser()) {
            return view('app.home');
        }

        return view('app.guest');
    }

}
