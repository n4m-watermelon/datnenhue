<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as BaseAuthenticate;
use Illuminate\Support\Arr;

class Authenticate extends BaseAuthenticate
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Closure $next
     * @param array $guards
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $this->authenticate($request, $guards);

        if (!$guards) {
            $route = $request->route()->getAction();
            $flag = Arr::get($route, 'permission', Arr::get($route, 'as'));
            if ($flag && !auth()->user()->hasAnyPermission((array)$flag)) {
                if ($request->expectsJson()) {
                    return response()->json(['error' => 'Unauthenticated.'], 401);
                }
                return redirect()->route('admin::dashboard.index')->withErrors(['Bạn không có quyền sử dụng chức năng']);
            }
        }

        return $next($request);
    }
}
