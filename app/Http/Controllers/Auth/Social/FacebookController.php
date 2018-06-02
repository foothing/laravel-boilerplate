<?php

namespace App\Http\Controllers\Auth\Social;

use App\Http\Controllers\Auth\Social\AbstractSocialController as Controller;
use Stats\Social\Services\Facebook\FacebookService;
use Cache;

class FacebookController extends Controller
{
    protected $facebookService;

    public function __construct(FacebookService $facebookService)
    {
        $this->driver = 'facebook';
        $this->facebookService = $facebookService;
        parent::__construct();
    }

    /**
     * Deauthorize Facebook user
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function deauthorizeUser()
    {
        $this->facebookService->deauthorizeUser();

        return redirect('/');
    }
}
