<?php namespace App\Services\Auth\Laravel;

use App\Services\Auth\LoginInterface;
use Illuminate\Support\Facades\Auth;

class LoginService implements LoginInterface {

    public function attempt(array $credentials, $remember = false) {
        $credentials['status'] = 'active';
        return Auth::attempt($credentials, $remember);
    }

    public function logout() {
        $user = Auth::user();
        $user->api_token = null;
        $user->save();
        return Auth::logout();
    }
}
