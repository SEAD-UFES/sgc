<?php

namespace App\Exceptions;

use App\Http\Middleware\EncryptCookies;
use App\Logging\Logger;
use App\Logging\LoggerInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Arr;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [];

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

    private LoggerInterface $logger;

    public function __construct(Container $container)
    {
        parent::__construct($container);

        $internalDontReport = $this->internalDontReport;
        /**
         * @var int $key
         */
        $key = array_search(\Symfony\Component\HttpKernel\Exception\HttpException::class, $internalDontReport, true);
        Arr::forget($internalDontReport, $key);
        $internalDontReport = array_values($internalDontReport);
        $this->internalDontReport = $internalDontReport;

        $this->logger = new Logger();
    }

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
    }

    public function report(Throwable $exception): void
    {
        if ($exception instanceof AuthorizationException) {
            abort(403);
        }

        if ($exception instanceof HttpException) {
            if ($exception->getStatusCode() === 404) {
                // https://laracasts.com/discuss/channels/laravel/getting-authuser-on-custom-404-page?reply=643642
                /**
                 * @var StartSession $sessionMiddleware
                 */
                $sessionMiddleware = resolve(StartSession::class);
                /**
                 * @var EncryptCookies $decrypter
                 */
                $decrypter = resolve(EncryptCookies::class);
                $decrypter->handle(request(), static fn () => $sessionMiddleware->handle(request(), static fn () => response('')));
            }

            try {
                $this->logger->logHttpError(request(), $exception);
            } catch (Throwable $e) {
            }
        } else {
            parent::report($exception);
        }
    }
}
