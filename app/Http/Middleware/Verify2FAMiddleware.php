<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class Verify2FAMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            // Check if 2FA is authenticated
            if (!session('two_factor_authenticated')) {
                \Log::info('2FA not authenticated, redirecting to 2FA page for user: ' . auth()->user()->email);
                return redirect()->route('two_factor.index');
            }
            
            // Check if 2FA session has expired (for remember 2FA)
            if (session('two_factor_expires_at')) {
                $expiresAt = Carbon::parse(session('two_factor_expires_at'));
                \Log::info('2FA session expires at: ' . $expiresAt . ' for user: ' . auth()->user()->email);
                
                if (now()->isAfter($expiresAt)) {
                    // Session expired, clear it and redirect to 2FA
                    session()->forget(['two_factor_authenticated', 'two_factor_expires_at']);
                    \Log::info('2FA session expired, clearing session for user: ' . auth()->user()->email);
                    return redirect()->route('two_factor.index');
                }
            }
        }
        
        return $next($request);
    }
}
