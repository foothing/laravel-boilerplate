<?php

namespace App\Http\Controllers\Auth\Social;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stats\Social\Services\SocialAccountService;
use Socialite;
use Lang;
use Config;
use Session;
use Log;

abstract class AbstractSocialController extends Controller
{
    protected $driver;
    protected $defaultRedirection;
    protected $defaultFailureRedirection;

    public function __construct(){
        $this->defaultRedirection = Config::get('mossocial.social.default_redirection');
        $this->defaultFailureRedirection = Config::get('mossocial.social.default_failure_redirection');
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

        return $driver->redirect();
    }

    /**
     * Redirect to a route after login
     * @param SocialAccountService $service
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function callback(SocialAccountService $service, Request $request)
    {
        // Check if user to log to social
        if ($request->has('denied')) {
            // TODO: Error message
            $redirect = '/';
        } else {
            $socialiteResponse = Socialite::driver($this->driver);
            $redirect = $service->checkUserExists($socialiteResponse, $this->driver);
            if(is_null($redirect)) {
                // TODO: Error message
                $redirect = '/';
            }
        }

        return redirect($redirect);
    }
}
