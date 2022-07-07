<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use Spatie\Backup\Events\BackupHasFailed;
use Spatie\Backup\Events\BackupWasSuccessful;
use Spatie\Backup\Events\UnhealthyBackupWasFound;

class BackupEventSubscriber
{
    /**
     * Handle user login events.
     */
    public function handleBackupFailed($event)
    {
        Log::error('Backup Failed');
    }

    /**
     * Handle user logout events.
     */
    public function handleBackupSuccessful($event)
    {
        Log::info('Backup Successful');
    }

    /**
     * Handle user login events.
     */
    public function handleBackupUnhealthy($event)
    {
        Log::error('Backup Unhealthy Found');
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     * @return array
     */
    public function subscribe($events)
    {
        return [
            BackupHasFailed::class => 'handleBackupFailed',
            BackupWasSuccessful::class => 'handleBackupSuccessful',
            UnhealthyBackupWasFound::class => 'handleBackupUnhealthy',
        ];
    }
}
