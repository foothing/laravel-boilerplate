<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Reminder extends Model {
    public $timestamps = false;

    public function getToken() {
        return $this->token;
    }

    public function getDates() {
        return ['created_at'];
    }
}
