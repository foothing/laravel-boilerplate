<?php namespace App\Services\Auth;

interface RegisterInterface {

    public function register(array $credentials);

    public function emailRegistration($user, $activationUrl);

    public function activate($token);

    public function emailActivation($user);
}
