<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Exception;

class AcceptOnlyJsonMiddleware {

    public function handle(Request $request, Closure $next)
    {
        if (!$request->isMethod('post')) {
            return $next($request);
        }

        $acceptHeader = $request->header('Content-Type');

        if ($acceptHeader !== 'application/json') {
            return response()->json(['message' => 'Invalid data format.'], 406);
        }

        try {
            $objects = json_decode($request->getContent(), TRUE, 512, JSON_THROW_ON_ERROR);
            $keys = array_keys($objects);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 406);
        }

        $request->merge([
            'objects'     => $objects,
            'object_keys' => $keys
        ]);

        return $next($request);
    }
}
