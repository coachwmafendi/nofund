<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::user();

                // If user has no organization, redirect to onboarding
                if (!$user->organization_id) {
                    return redirect()->route('onboarding');
                }

                // Otherwise redirect to dashboard
                return redirect()->route('dashboard');
            }
        }

        return $next($request);
    }
}