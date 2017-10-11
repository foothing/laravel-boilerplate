<?php namespace App\Services\Auth;

interface PasswordResetInterface {

    public function beginReset($email);

    public function emailBeginReset($email, $url);

    public function finalizeReset($token, $password);
}
