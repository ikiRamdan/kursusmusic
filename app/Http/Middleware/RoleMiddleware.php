<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
   public function handle(Request $request, Closure $next, ...$roles)
{
    if (!auth()->check()) {
        return $request->expectsJson()
            ? response()->json(['message' => 'Unauthorized'], 401)
            : redirect('/login');
    }

    if (!in_array(auth()->user()->role, $roles)) {
        return $request->expectsJson()
            ? response()->json(['message' => 'Forbidden'], 403)
            : abort(403);
    }

    if (auth()->user()->status !== 'active') {
        return $request->expectsJson()
            ? response()->json(['message' => 'User not active'], 403)
            : redirect('/login')->with('error', 'User tidak aktif');
    }

    return $next($request);
}
}