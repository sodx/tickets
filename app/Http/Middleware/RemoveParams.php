<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RemoveParams
{
    public function handle($request, $next)
    {
        $requestParams = request()->query();
        $allowedParams = [];
        if (Str::contains(request()->getRequestUri(), '/admin')) {
            return $next($request);
        }
        if (Str::contains(request()->getRequestUri(), ['/events', '/segment', '/genre'])) {
            $allowedParams = ['page', 'sort', 'order', 'search', 'per_page', 'date', 'date_to'];
        }
        foreach ($requestParams as $key => $value) {
            if (in_array($key, $allowedParams)) {
                unset($requestParams[$key]);
            }
        }
        if (!empty($requestParams)) {
            throw new NotFoundHttpException();
        }
        return $next($request);
    }
}
