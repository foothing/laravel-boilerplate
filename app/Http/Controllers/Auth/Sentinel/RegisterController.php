<?php namespace App\Http\Controllers\Auth\Sentinel;

use App\Http\Traits\FlashesMessages;
use App\Services\Auth\Sentinel\RegisterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class RegisterController {

    use FlashesMessages;

    /**
     * @var \App\Auth\Sentinel\RegisterService
     */
    protected $service;

    public function __construct(RegisterService $service) {
        $this->service = $service;
    }

    public function getRegister() {
        return view('app.register');
    }

    public function postRegister(Request $request) {
        try {
            $input = $request->all();
            $rules = ['email' => 'required|email|unique:users','password' => 'required|confirmed','g-recaptcha-response' => 'required|recaptcha',];
            $validator = Validator::make($input, $rules);

            if ($validator->fails()) {
                return Redirect::back()->withInput()->withErrors($validator);
            }

            if ($user = $this->service->register($input)) {
                //$this->putMessage("fatto");
                //return $this->showMessage();
                return redirect('/');
            }

            $errors = trans('auth.failed.creation');
        }

        catch (\Exception $ex) {
            $errors = $ex->getMessage();
        }

        return Redirect::back()->withInput()->withErrors($errors);
    }

    public function getActivate($token) {
        try {
            $this->service->activate($token);
            return redirect()->route('auth.login')->with('message', trans('auth.activated'));
        }

        catch (\Exception $ex) {
            \Log::warning($ex->getMessage());
            abort(404);
        }
    }
}
