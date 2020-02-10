<?php

namespace Sil\Scaffold;

use Closure;
use Illuminate\Support\Facades\Auth;

class SilScaffoldMiddleware
{
    public function handle($request, Closure $next, $guard = null)
    {
        if ( !Auth::guard('scaffold_user')->check() ){
            return redirect('/admin/login');
        }
        return $next($request);
    }
}
