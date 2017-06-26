<?php namespace App\Services\Auth\Sentinel;

use App\Mail\PasswordForgotten;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\Auth\PasswordResetException;
use Cartalyst\Sentinel\Laravel\Facades\Reminder;
use Cartalyst\Sentinel\Sentinel;
use Illuminate\Support\Facades\Mail;

class PasswordService {

    /**
     * @var \Cartalyst\Sentinel\Sentinel
     */
    protected $sentinel;

    /**
     * @var \App\Repositories\User\UserRepositoryInterface
     */
    protected $users;

    public function __construct(Sentinel $sentinel, UserRepositoryInterface $users) {
        $this->sentinel = $sentinel;
        $this->users = $users;
    }

    public function beginReset($email) {
        if (! $user = $this->users->findByEmail($email)) {
            throw new PasswordResetException("User not found: $email");
        }

        if (! $reminder = Reminder::create($user)) {
            throw new PasswordResetException("Cannot create reminder: $email");
        }

        $url = route('auth.reset', ['token' => $reminder->code]);

        $this->emailBeginReset($email, $url);

        return true;
    }

    protected function emailBeginReset($email, $url) {
        if (! $user = $this->users->findByEmail($email)) {
            throw new PasswordResetException("User not found: $email");
        }

        Mail::to($email)->send(new PasswordForgotten($url));
    }

    public function finalizeReset($token, $password) {
        $reminder = Reminder::createModel()
            ->whereCode($token)
            ->first();

        if (! $reminder) {
            throw new PasswordResetException("Reminder not found: $token");
        }

        if (! $user = $this->sentinel->findById($reminder->user_id)) {
            throw new PasswordResetException("User not found with token: $token");
        }

        return Reminder::complete($user, $token, $password);
    }
}
