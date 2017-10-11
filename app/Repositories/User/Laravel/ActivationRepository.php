<?php namespace App\Repositories\User\Laravel;

use App\Activation;
use App\Repositories\User\ActivationRepositoryInterface;
use Foothing\Repository\Eloquent\EloquentRepository;

class ActivationRepository extends EloquentRepository implements ActivationRepositoryInterface {

    public function __construct(Activation $model) {
        parent::__construct($model);
    }

    public function findByCode($code) {
        return $this->model
            ->whereCode($code)
            ->whereCompleted(false)
            ->first();
    }
}
