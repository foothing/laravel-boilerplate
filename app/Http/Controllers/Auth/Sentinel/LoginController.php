<?php namespace App\Http\Controllers\Auth\Sentinel;

use App\Http\Traits\FlashesMessages;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller {

    use FlashesMessages;

    /**
     * @var \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected $guard;

    public function __construct(StatefulGuard $guard){
        $this->guard = $guard;
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
        try {
            $input = $request->all();
            $rules = ['email' => 'required|email','password' => 'required',];
            $validator = Validator::make($input, $rules);

            if ($validator->fails()) {
                return Redirect::back()->withInput()->withErrors($validator);
            }

            $remember = (bool)Input::get('remember', false);
            if ($this->guard->attempt($input, $remember)) {
                return Redirect::intended('/');
            }

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
        $this->guard->logout();
        return redirect()->to('/');
    }

    public function getUser() {
        $user = $this->guard->user();
        return $user ? $user->load('roles') : null;
    }
}
