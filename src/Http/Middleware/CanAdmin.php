<?php

namespace Brackets\AdminAuth\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;

/**
 * Class CanAdmin
 *
 * @package Brackets\AdminAuth\Http\Middleware
 */
class CanAdmin
{
    /**
     * Guard used for admin user
     *
     * @var string
     */
    protected $guard = 'admin';

    /**
     * CanAdmin constructor.
     */
    public function __construct()
    {
        $this->guard = config('admin-auth.defaults.guard');
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::guard($this->guard)->user()->getAllPermissions();
        if (Auth::guard($this->guard)->check() && Auth::guard($this->guard)->user()->can('admin')) {
            return $next($request);
        }

        if (!Auth::guard($this->guard)->check()) {
            return redirect()->guest('/admin/login');
        } else {
            throw new UnauthorizedException('Unathorized');
        }
    }
}
