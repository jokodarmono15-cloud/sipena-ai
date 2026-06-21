<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\AuditLog;

class AuditLogging
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response->getStatusCode() < 400 && auth()->check()) {
            if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
                AuditLog::create([
                    'user_id' => auth()->id(),
                    'action' => $request->method() . ' ' . $request->path(),
                    'model' => $request->route()?->getName(),
                    'model_id' => $request->route('id') ?? 0,
                    'changes' => $request->except('_token', 'password', 'password_confirmation'),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
            }
        }

        return $response;
    }
}
