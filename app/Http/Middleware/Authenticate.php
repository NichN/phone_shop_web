<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }

    protected function authenticate($request, array $guards)
    {
        parent::authenticate($request, $guards);
        $user = auth()->user();
        // If user is authenticated but has no role, log out and redirect
        if ($user && !$user->role_id) {
            \Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            abort(redirect()->route('login')->withErrors(['email' => 'You do not have permission to access the dashboard.']));
        }
    }
}
