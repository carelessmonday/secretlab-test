<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AcceptOnlyJsonMiddleware {

    public function handle(Request $request, Closure $next)
    {
        if (!$request->isMethod('post')) {
            return $next($request);
        }

        $acceptHeader = $request->header('Content-Type');
        if ($acceptHeader !== 'application/json') {
            return response()->json([], 406);
        }

        return $next($request);
    }
}
