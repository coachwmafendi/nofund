<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureHasOrganization
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && !Auth::user()->organization_id) {
            return redirect()->route('onboarding');
        }

        return $next($request);
    }
}