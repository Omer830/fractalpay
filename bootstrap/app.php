<?php

use App\Exceptions\Handler;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use App\Exceptions\ErrorException;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(
            except: ['stripe/*']
        );
        $middleware->web(append: [
            HandleInertiaRequests::class,
        ]);
        $middleware->api(append: [
             \Illuminate\Session\Middleware\StartSession::class
        ]);
        $middleware->append(\App\Http\Middleware\CacheUserTimezone::class);
        $middleware->append(\App\Http\Middleware\ApplyTimezone::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->render(function (\App\Exceptions\InertiaException $e, Request $request) {

            return Inertia::render($e->getComponent(), $e->getErrors());
//            if (app()->environment(['local', 'testing']) && in_array($response->getStatusCode(), [500, 503, 404, 403])) {
//                return Inertia::render('Login/Login', ['errors' => ['email' => 'invalid login details']])
//                    ->toResponse($request)
//                    ->setStatusCode(403);
//            } elseif ($response->getStatusCode() === 419) {
//                return back()->with([
//                    'message' => 'The page expired, please try again.',
//                ]);
//            }
//
//            return $response;
        });

        $exceptions->render(function (AccessDeniedHttpException $e, Request $request) {
            return (new Handler($e))->response();
        });


        $exceptions->render(function (ErrorException $e, Request $request) {
            return (new Handler($e))->response();
        });

        // Example for handling a built-in exception
        $exceptions->render(function (Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return ((new Handler($e))->response());
            }
        });
    })->create();
