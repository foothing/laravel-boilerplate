<?php namespace App\Http\Controllers\Auth\Laravel;

use App\Services\Auth\PasswordResetException;
use App\Services\Auth\PasswordResetInterface;
use App\Services\Auth\Sentinel\PasswordService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class PasswordController {

    /**
     * @var \App\Services\Auth\Sentinel\PasswordService
     */
    protected $service;

    public function __construct(PasswordResetInterface $service) {
        $this->service = $service;
    }

    public function getPassword() {
        return view('app.lost');
    }

    public function postPassword(Request $request) {
        $email = $request->only('email');

        $validator = Validator::make($email, ['email' => 'required|email']);

        if ($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }

        try {
            $this->service->beginReset($request->get('email'));
            return redirect()->route('auth.login')->with('message', trans('auth.password'));
        }

        catch (PasswordResetException $ex) {
            \Log::warning($ex->getMessage());
            // Let's pretend we actually sent a mail.
            return redirect()->route('auth.login')->with('message', trans('auth.password'));
        }

        catch (\Exception $ex) {
            \Log::warning($ex->getMessage());
            return Redirect::back()->withInput()->withErrors(trans('app.error'));
        }
    }

    public function getReset($token) {
        return view('app.reset')->with('token', $token);
    }

    public function postReset(Request $request) {
        $input = $request->all();

        $validator = Validator::make($input, ['password' => 'required|confirmed']);

        if ($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }

        try {
            $this->service->finalizeReset($input['token'], $input['password']);
        }

        catch (PasswordResetException $ex) {
            \Log::warning($ex->getMessage());
            return redirect()->route('auth.login')->with('message', trans('app.error'));
        }

        catch (\Exception $ex) {
            \Log::warning($ex->getMessage());
            return Redirect::back()->withInput()->withErrors(trans('app.error'));
        }

        return redirect()->route('auth.login')->with('message', trans('auth.reset'));
    }
}
