<?php namespace App\Http\Traits;

use Illuminate\Support\Facades\Session;

trait FlashesMessages {

    protected function getMessage() {
        return Session::get('messages.message');
    }

    protected function putMessage($message, $title = null) {
        return Session::flash('messages.message', ['message' => $message, 'title' => $title]);
    }

    protected function showMessage() {
        if ($message = $this->getMessage()) {
            return view('app.message', $message);
        }

        abort(404);
    }
}
