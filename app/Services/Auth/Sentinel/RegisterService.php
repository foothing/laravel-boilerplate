<?php namespace App\Services\Auth\Sentinel;

use App\Mail\UserActivated;
use App\Mail\UserRegistered;
use App\Services\Auth\CannotActivateUserException;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Support\Facades\Mail;

class RegisterService {

    public function register(array $credentials, $url) {
        if (! $user = Sentinel::register($credentials)) {
            return false;
        }

        if (! $activation = Activation::create($user)) {
            return false;
        }

        $activationUrl = $this->getActivationUrl($url, $activation->getCode());
        $this->emailRegistration($user, $activationUrl);
        return $user;
    }

    protected function getActivationUrl($url, $activationCode) {
        return rtrim($url, " /") . "/" . $activationCode;
    }

    protected function emailRegistration($user, $activationUrl) {
        Mail::to($user->email)->send(new UserRegistered($user, $activationUrl));
    }

    public function activate($token) {
        $activation = Activation::createModel()
            ->whereCode($token)
            ->whereCompleted(false)
            ->first();

        if (! $activation) {
            throw new CannotActivateUserException("token not found $token");
        }

        if (! $user = Sentinel::findUserById($activation->user_id)) {
            throw new CannotActivateUserException("user not found for $token");
        }

        if (! Activation::complete($user, $token)) {
            throw new CannotActivateUserException("cannot complete with $token");
        }

        $this->emailActivation($user);

        return $user;
    }

    protected function emailActivation($user) {
        Mail::to($user->email)->send(new UserActivated($user));
    }
}
