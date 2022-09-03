<?php

namespace App\Logging;

use Closure;
use Illuminate\Support\Arr;
use Spatie\Activitylog\Contracts\LoggablePipe;
use Spatie\Activitylog\EventLogBag;

class RemoveKeyFromLogChangesPipe implements LoggablePipe
{
    public function __construct(protected string $field)
    {
    }

    public function handle(EventLogBag $event, Closure $next): EventLogBag
    {
        Arr::forget($event->changes, ["attributes.{$this->field}", "old.{$this->field}"]);

        return $next($event);
    }
}
