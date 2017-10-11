<?php namespace App\Repositories\User\Laravel;

use App\Reminder;
use App\Repositories\User\ReminderRepositoryInterface;
use Carbon\Carbon;
use Foothing\Repository\Eloquent\EloquentRepository;

class ReminderRepository extends EloquentRepository implements ReminderRepositoryInterface {

    public function __construct(Reminder $model) {
        parent::__construct($model);
    }

    public function create($reminder) {
        // If an existing, not expired reminder exists
        // just return that one.
        $existing = $this->model
            ->whereEmail($reminder->email)
            ->where('created_at', '>', Carbon::now()->subDays(7))
            ->first();

        if ($existing) {
            return $existing;
        }

        // Delete expired reminders.
        $this->delete($reminder);

        // Create one.
        return parent::create($reminder);
    }

    public function findByToken($token) {
        return $this->model
            ->whereToken($token)
            ->where('created_at', '>', Carbon::now()->subDays(7))
            ->first();
    }

    public function delete($reminder) {
        $this->model->whereEmail($reminder->email)->delete();
    }
}
