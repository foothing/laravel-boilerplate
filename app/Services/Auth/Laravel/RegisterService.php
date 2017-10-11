<?php namespace App\Services\Auth\Laravel;

use App\Activation;
use App\Mail\UserActivated;
use App\Mail\UserRegistered;
use App\Repositories\User\ActivationRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\Auth\RegisterInterface;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RegisterService implements RegisterInterface {

    /**
     * @var \App\Repositories\User\UserRepositoryInterface
     */
    protected $users;

    /**
     * @var \App\Repositories\ActivationRepositoryInterface
     */
    protected $activations;

    public function __construct(UserRepositoryInterface $users, ActivationRepositoryInterface $activations) {
        $this->users = $users;
        $this->activations = $activations;
    }

    public function register(array $credentials) {
        $credentials['password'] = Hash::make($credentials['password']);

        if (! $user = $this->users->create(new User($credentials))) {
            return false;
        }

        if (! $activation = $this->makeActivation($user)) {
            return false;
        }

        $activationUrl = route('auth.activate', ['token' => $activation->getCode()]);
        $this->emailRegistration($user, $activationUrl);
        return $user;
    }

    protected function makeActivation($user) {
        if (! $user) {
            return null;
        }

        $activation = new Activation();
        $activation->user_id = $user->id;
        $activation->code = $this->makeActivationCode($user);
        $activation->completed = false;

        return $this->activations->create($activation);
    }

    public  function makeActivationCode($user) {
        return hash('ripemd160', rand(0, 255 ) . sha1($user->email) . time());
    }

    public function emailRegistration($user, $activationUrl) {
        Mail::to($user->email)->send(new UserRegistered($user, $activationUrl));
    }

    public function activate($token) {
        if (! $activation = $this->activations->findByCode($token)) {
            throw new CannotActivateUserException("token not found $token");
        }

        if (! $user = $this->users->find($activation->user_id)) {
            throw new CannotActivateUserException("user not found for $token");
        }

        if (! $this->completeActivation($user, $token)) {
            throw new CannotActivateUserException("cannot complete with $token");
        }

        $this->emailActivation($user);

        return $user;
    }

    protected function completeActivation($user, $token) {
        if (! $activation = $this->activations->findByCode($token)) {
            throw new CannotActivateUserException("token not found $token");
        }

        if ($user->id != $activation->user_id) {
            throw new CannotActivateUserException("activation mismatch");
        }

        \DB::beginTransaction();

        $user->status = 'active';
        $user = $this->users->update($user);

        $activation->completed = true;
        $activation->completed_at = Carbon::now();
        $this->activations->update($activation);

        \DB::commit();

        return $user;
    }

    public function emailActivation($user) {
        Mail::to($user->email)->send(new UserActivated($user));
    }
}
