<?php namespace App\Http\Middleware;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Closure;

class SentinelGuest
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next)
    {
        if (Sentinel::check()) {
            if ($request->ajax()) {
                $message = $this->translate('unauthorized', 'Unauthorized');
                return response()->json(['error' => $message], 401);
            } else {
                return redirect()->guest('/');
            }
        }


        return $next($request);
    }

/**
 * Determine if the user is logged in to any of the given guards.
 *
 * @param  array  $guards
 * @return void
 *
 * @throws \Illuminate\Auth\AuthenticationException
 */
protected function authenticate(array $guards)
{
    if (empty($guards)) {
        return $this->auth->authenticate();
    }

    foreach ($guards as $guard) {
        if ($this->auth->guard($guard)->check()) {
            return $this->auth->shouldUse($guard);
        }
    }

    throw new AuthenticationException('Unauthenticated.', $guards);
}
}
