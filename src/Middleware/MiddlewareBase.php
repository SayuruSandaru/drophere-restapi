<?php

namespace App\Middleware;

abstract class MiddlewareBase
{
    abstract public function handle($request, $next);
}
