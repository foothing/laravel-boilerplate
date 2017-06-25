<?php namespace App\Http\Traits;

use Illuminate\Support\Facades\Session;

trait FlashesMessages {

    protected function getMessage() {
        return Session::get('messages.message');
    }

    protected function putMessage($message, $type = 'success', $title = null) {
        return Session::flash('messages.message', (object)[
            'text' => $message,
            'type' => $type,
            'title' => $title,
        ]);
    }

    protected function showMessage($message, $type = 'success', $title = null) {
        $this->putMessage($message, $type, $title);

        return redirect()->route('message');
    }
}
