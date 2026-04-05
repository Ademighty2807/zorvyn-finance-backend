<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role'               => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission'         => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })


    ->withExceptions(function (Exceptions $exceptions): void {

        // ✅ Validation Error (422)
        $exceptions->render(function (ValidationException $e, $request) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation failed',
                'errors'  => $e->errors(),
            ], 422);
        });

        // ✅ Model Not Found (404)
        $exceptions->render(function (ModelNotFoundException $e, $request) {
            return response()->json([
                'status'  => false,
                'message' => 'Resource not found',
                'errors'  => null,
            ], 404);
        });

        // ✅ Unauthenticated (401)
        $exceptions->render(function (UnauthorizedHttpException $e, $request) {
            return response()->json([
                'status'  => false,
                'message' => 'Unauthenticated',
                'errors'  => null,
            ], 401);
        });

        // ✅ Fallback (any other error)
        $exceptions->render(function (\Throwable $e, $request) {

            return response()->json([
                'status'  => false,
                'message' => app()->isLocal()
                    ? $e->getMessage()   // show real error in dev
                    : 'Something went wrong',
                'errors'  => null,
            ], 500);
        });

    })
    ->create();
