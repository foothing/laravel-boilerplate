<?php namespace App\Services\Auth;

interface LoginInterface {

    public function attempt(array $credentials, $remember);

    public function logout();

}
