<?php namespace App\Repositories\User;

interface ReminderRepositoryInterface {

    public function create($reminder);

    public function findByToken($token);

    public function delete($reminder);

}
