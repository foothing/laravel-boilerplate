<?php namespace App\Repositories\User\Laravel;

use App\Repositories\User\UserRepositoryInterface;
use App\User;
use Foothing\Repository\Eloquent\EloquentRepository;

class UserRepository extends EloquentRepository implements UserRepositoryInterface {

    public function __construct(User $user) {
        parent::__construct($user);
    }

    public function findByEmail($email) {
        return $this->model
            ->whereStatus('active')
            ->whereEmail($email)
            ->first();
    }
}
