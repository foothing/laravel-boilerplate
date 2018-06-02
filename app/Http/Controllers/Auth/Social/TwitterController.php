<?php

namespace App\Http\Controllers\Auth\Social;

use App\Http\Controllers\Auth\Social\AbstractSocialController as Controller;
use Stats\Social\Services\Twitter\TwitterService;

class TwitterController extends Controller
{
    protected $twitterService;

    public function __construct(TwitterService $twitterService)
    {
        $this->driver = 'twitter';
        $this->twitterService = $twitterService;
        parent::__construct();
    }
}
