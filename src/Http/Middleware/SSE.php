<?php

namespace Mcms\FrontEnd\Http\Middleware;

use Closure;

class SSE
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('X-Accel-Buffering', 'no');

        return $response;
    }
}
