<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminSession
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->has('admin_user')) {
            $request->session()->put('admin_intended', $request->fullUrl());

            return redirect()->route('admin.login');
        }

        return $next($request);
    }
}
