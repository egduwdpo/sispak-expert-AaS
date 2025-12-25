<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPakarRole
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || !$request->user()->isPakar()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Pakar role required.',
            ], 403);
        }

        return $next($request);
    }
}
