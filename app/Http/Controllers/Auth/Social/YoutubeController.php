<?php

namespace App\Http\Controllers\Auth\Social;

use App\Http\Controllers\Auth\Social\AbstractSocialController as Controller;
use Config;
use Socialite;

class YoutubeController extends Controller
{
    public function __construct()
    {
        $this->driver = 'youtube';
        parent::__construct();
    }

    /**
     * @return mixed
     */
    public function redirect()
    {
        $driver = Socialite::driver($this->driver);
        $scopes = Config::get('mossocial.social.scopes.'.$this->driver, false);
        if ($scopes) {
            $driver->scopes($scopes);
        }

        $driver->with(['access_type' => 'offline', 'prompt' => 'consent']);

        return $driver->redirect();
    }
}
