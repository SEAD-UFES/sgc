<?php

namespace App\Logging;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Symfony\Component\HttpKernel\Exception\HttpException;

interface LoggerInterface
{
    /**
     * @param Request $request
     * @param HttpException $exception
     *
     * @return void
     */
    public static function logHttpError(Request $request, HttpException $exception): void;

    /**
     * @param Activity|string $activity
     * @param Model|null $model
     *
     * @return void
     */
    public static function logModelEvent(Activity|string $activity, Model|string|null $model = null): void;

    public static function logDomainEvent(string $event, ?Model $model = null): void;

    public static function logSystemEvent(string $event, Model|string|null $model = null): void;
}
