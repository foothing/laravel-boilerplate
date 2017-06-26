<?php namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;

class MessageController {

    public function getIndex() {
        if ($message = Session::get('messages.message')) {
            return view('app.message')->with('message', $message);
        }

        return redirect('/');
    }
}
