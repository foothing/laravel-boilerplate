<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Activation extends Model {

    public function getCode() {
        return $this->code;
    }

    public function getDates() {
        return ['created_at', 'updated_at', 'completed_at'];
    }
}
