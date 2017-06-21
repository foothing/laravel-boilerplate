<?php namespace App\Auth\Sentinel;

use Cartalyst\Sentinel\Sentinel;
use Cartalyst\Sentinel\Users\EloquentUser;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\StatefulGuard;

class Stateful implements StatefulGuard {

    /**
     * @var \Cartalyst\Sentinel\Sentinel
     */
    protected $sentinel;

    public function __construct(Sentinel $sentinel) {
        $this->sentinel = $sentinel;
    }

    /**
     * Determine if the current user is authenticated.
     *
     * @return bool
     */
    public function check() {
         return $this->sentinel->check();
    }

    /**
     * Determine if the current user is a guest.
     *
     * @return bool
     */
    public function guest() {
        return $this->sentinel->guest();
    }

    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user() {
        return $this->sentinel->getUser();
    }

    /**
     * Get the ID for the currently authenticated user.
     *
     * @return int|null
     */
    public function id() {
        if ($user = $this->user()) {
            return $user->id;
        }

        return null;
    }

    /**
     * Validate a user's credentials.
     *
     * @param  array $credentials
     *
     * @return bool
     */
    public function validate(array $credentials = []) {
        return $this->sentinel->getUserRepository()->validForCreation($credentials);
    }

    /**
     * Set the current user.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     *
     * @return void
     */
    public function setUser(Authenticatable $user) {
        return $this->sentinel->setUser($user);
    }

    /**
     * Attempt to authenticate a user using the given credentials.
     *
     * @param  array $credentials
     * @param  bool  $remember
     * @param  bool  $login
     *
     * @return bool
     */
    public function attempt(array $credentials = [], $remember = false, $login = true) {
        return $this->sentinel->authenticate($credentials, $remember, $login);
    }

    /**
     * Log a user into the application without sessions or cookies.
     *
     * @param  array $credentials
     *
     * @return bool
     */
    public function once(array $credentials = []) {
        if ($this->attempt($credentials)) {
            $this->setUser($this->lastAttempted);

            return true;
        }

        return false;
    }

    /**
     * Log a user into the application.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @param  bool                                       $remember
     *
     * @return void
     */
    public function login(Authenticatable $user, $remember = false) {
        return $this->sentinel->login($user, $remember);
    }

    /**
     * Log the given user ID into the application.
     *
     * @param  mixed $id
     * @param  bool  $remember
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    public function loginUsingId($id, $remember = false) {
        throw new \Exception(__METHOD__ . " not implemented");
    }

    /**
     * Log the given user ID into the application without sessions or cookies.
     *
     * @param  mixed $id
     *
     * @return bool
     */
    public function onceUsingId($id) {
        throw new \Exception(__METHOD__ . " not implemented");
    }

    /**
     * Determine if the user was authenticated via "remember me" cookie.
     *
     * @return bool
     */
    public function viaRemember() {
        throw new \Exception(__METHOD__ . " not implemented");
    }

    /**
     * Log the user out of the application.
     *
     * @return void
     */
    public function logout() {
        return $this->sentinel->logout();
    }
}
