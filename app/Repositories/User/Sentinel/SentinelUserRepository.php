<?php namespace App\Repositories\User\Sentinel;

use App\Repositories\User\UserRepositoryInterface;
use Cartalyst\Sentinel\Users\EloquentUser;
use Foothing\Repository\Eloquent\EloquentRepository;

class SentinelUserRepository extends EloquentRepository implements UserRepositoryInterface {

    public function __construct(EloquentUser $user) {
        parent::__construct($user);
    }

    public function findByEmail($email) {
        return $this->findOneBy('email', $email);
    }
}
