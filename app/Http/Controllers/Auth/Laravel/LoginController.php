<?php namespace App\Http\Controllers\Auth\Laravel;

use App\Http\Traits\FlashesMessages;
use App\Services\Auth\LoginInterface;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Illuminate\Auth\SessionGuard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller {

    use FlashesMessages, ThrottlesLogins;

    /**
     * @var \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected $guard;

    /**
     * @var \App\Services\Auth\LoginInterface
     */
    protected $service;

    public function __construct(LoginInterface $service){
        $this->guard = Auth::guard();
        $this->service = $service;
    }

    /**
     * Login page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getLogin() {
        return view('app/login');
    }

    /**
     * Handle login.
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function postLogin(Request $request) {
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        try {
            $input = $request->except('_token');
            $rules = ['email' => 'required|email','password' => 'required',];
            $validator = Validator::make($input, $rules);

            if ($validator->fails()) {
                return Redirect::back()->withInput()->withErrors($validator);
            }

            $remember = (bool)Input::get('remember', false);
            if ($this->service->attempt($input, $remember)) {
                return Redirect::intended('/');
            }

            $this->incrementLoginAttempts($request);

            $errors = trans('auth.failed');
        }

        catch (NotActivatedException $e) {
            //$errors = 'Account is not activated!';
            return Redirect::to('reactivate')->with('user', $e->getUser());
        }

        catch (ThrottlingException $e) {
            $errors = trans('auth.throttle', ['seconds' => $e->getDelay()]);
        }

        catch (\Exception $ex) {
            $errors = $ex->getMessage();
        }

        return Redirect::back()->withInput()->withErrors($errors);
    }

    public function getLogout() {
        $this->service->logout();
        return redirect()->to('/');
    }

    public function getUser() {
        $user = $this->guard->user();
        return $user ? $user->load('roles') : null;
    }

    protected function username() {
        return "email";
    }
}
