<?php namespace App\Repositories\User;

interface ActivationRepositoryInterface {

    public function create($activation);

    public function findByCode($code);

    public function delete($activation);
}
