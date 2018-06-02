<?php

namespace App\Http\Controllers\Auth\Social;

use App\Http\Controllers\Auth\Social\AbstractSocialController as Controller;

class InstagramController extends Controller
{
    public function __construct()
    {
        $this->driver = 'instagram';
        parent::__construct();
    }
}
