<?php

namespace App\Exceptions;

use App\Helpers\SgcLogHelper;
use App\Http\Middleware\EncryptCookies;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [

    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [

    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(static function (Throwable $e) {
        });
    }

    public function report(Throwable $exception): void
    {
        if ($exception instanceof AuthorizationException && $request = request()) {
            SgcLogHelper::logBadAttemptOnUri($request, 403);
            Log::warning(
                'AuthorizationException: Forbidden access to ' . $request->path() . "\n" . $exception::class,
                [
                    'user' => auth()->user(),
                    'request' => $request,
                ]
            );
        }

        if ($exception instanceof AccessDeniedHttpException && $request = request()) {
            SgcLogHelper::logBadAttemptOnUri($request, 403);
            Log::warning(
                'AccessDeniedHttpException: Forbidden access to ' . $request->path() . "\n" . $exception::class,
                [
                    'user' => auth()->user(),
                    'request' => $request,
                ]
            );
        }

        if ($exception instanceof HttpException && $exception->getStatusCode() === 403 && $request = request()) {
            SgcLogHelper::logBadAttemptOnUri($request, 403);
            Log::warning(
                'HttpException:403: Forbidden access to ' . $request->path() . "\n" . $exception::class,
                [
                    'user' => auth()->user(),
                    'request' => $request,
                ]
            );
        }

        if ($exception instanceof HttpException && $exception->getStatusCode() === 401 && $request = request()) {
            SgcLogHelper::logBadAttemptOnUri($request, 401);
            Log::warning(
                'HttpException:401: Unauthorized access to ' . $request->path() . "\n" . $exception::class,
                [
                    'email' => $request->email,
                    'request' => $request,
                ]
            );
        }

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  Request  $request
     * @param  Throwable  $e
     *
     * @return Response
     *
     * @throws Throwable
     */
    public function render($request, Throwable $e): Response
    {
        if ($e instanceof NotFoundHttpException && $request = request()) {
            // https://laracasts.com/discuss/channels/laravel/getting-authuser-on-custom-404-page?reply=643642
            // @var StartSession $sessionMiddleware
            $sessionMiddleware = resolve(StartSession::class);

            // @var EncryptCookies $decrypter
            $decrypter = resolve(EncryptCookies::class);
            $decrypter->handle(request(), static fn () => $sessionMiddleware->handle(request(), static fn () => response('')));

            SgcLogHelper::logBadAttemptOnUri($request, 404);
            Log::warning(
                'NotFoundHttpException:404: Not found access to ' . $request->path() . "\n" . $e::class,
                [
                    'user' => auth()->user(),
                    'request' => $request,
                ]
            );
        }

        return parent::render($request, $e);
    }
}
