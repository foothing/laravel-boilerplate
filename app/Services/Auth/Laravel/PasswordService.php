<?php namespace App\Services\Auth\Laravel;

use App\Mail\PasswordForgotten;
use App\Reminder;
use App\Repositories\User\ReminderRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\Auth\PasswordResetException;
use App\Services\Auth\PasswordResetInterface;
use Carbon\Carbon;
use Cartalyst\Sentinel\Sentinel;
use Illuminate\Support\Facades\Mail;

class PasswordService implements PasswordResetInterface {

    /**
     * @var \App\Repositories\User\UserRepositoryInterface
     */
    protected $users;

    /**
     * @var \App\Repositories\User\ReminderRepositoryInterface
     */
    protected $reminders;

    public function __construct(UserRepositoryInterface $users, ReminderRepositoryInterface $reminders) {
        $this->users = $users;
        $this->reminders = $reminders;
    }

    public function beginReset($email) {
        if (! $user = $this->users->findByEmail($email)) {
            throw new PasswordResetException("User not found: $email");
        }

        if (! $reminder = $this->createReminder($user)) {
            throw new PasswordResetException("Cannot create reminder: $email");
        }

        $url = route('auth.reset', ['token' => $reminder->getToken()]);

        $this->emailBeginReset($email, $url);

        return true;
    }

    public function createReminder($user) {
        $reminder = new Reminder();
        $reminder->email = $user->email;
        $reminder->token = str_random(64);
        $reminder->created_at = Carbon::now();
        return $this->reminders->create($reminder);
    }

    public function emailBeginReset($email, $url) {
        if (! $user = $this->users->findByEmail($email)) {
            throw new PasswordResetException("User not found: $email");
        }

        Mail::to($email)->send(new PasswordForgotten($url));
    }

    public function finalizeReset($token, $password) {
        if (! $reminder = $this->reminders->findByToken($token)) {
            throw new PasswordResetException("Reminder not found: $token");
        }

        if (! $user = $this->users->findByEmail($reminder->email)) {
            throw new PasswordResetException("User not found with token: $reminder->email $token");
        }

        \DB::beginTransaction();

        $user->password = \Hash::make($password);
        $user = $this->users->update($user);

        $this->reminders->delete($reminder);

        \DB::commit();

        return $user;
    }
}
