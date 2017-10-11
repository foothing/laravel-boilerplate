<?php namespace App\Repositories\User;

interface UserRepositoryInterface {

    public function find($id);

    public function findByEmail($email);

}
