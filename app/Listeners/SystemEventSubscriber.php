<?php

namespace App\Listeners;

use App\Events\DocumentExported;
use App\Events\FileImported;
use App\Events\NotificationDismissed;
use App\Logging\LoggerInterface;
use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\Auth\Authenticatable;

class SystemEventSubscriber
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    /**
     * Handle user login events.
     *
     * @param  Login  $event
     *
     * @return void
     */
    public function handleUserLogin(Login $event)
    {
        /**
         * @var Authenticatable $authenticatableUser
         */
        $authenticatableUser = $event->user;

        /**
         * @var User $modelUser
         */
        $modelUser = $authenticatableUser;

        $this->logger->logSystemEvent(
            'user_login',
            $modelUser
        );
    }

    /**
     * Handle user logout events.
     *
     * @param  Logout  $event
     *
     * @return void
     */
    public function handleUserLogout(Logout $event)
    {
        /**
         * @var Authenticatable $authenticatableUser
         */
        $authenticatableUser = $event->user;

        /**
         * @var User $modelUser
         */
        $modelUser = $authenticatableUser;

        $this->logger->logSystemEvent(
            'user_logout',
            $modelUser
        );
    }

    /**
     * Handle file imported events.
     *
     * @param  FileImported  $event
     *
     * @return void
     */
    public function handleFileImported(FileImported $event)
    {
        $this->logger->logSystemEvent(
            'file_imported',
            $event->fileName
        );
    }

    /**
     * Handle bond document exported events.
     *
     * @param  DocumentExported  $event
     *
     * @return void
     */
    public function handleDocumentExported(DocumentExported $event)
    {
        $this->logger->logSystemEvent(
            'bond_document_exported',
            $event->bond,
        );
    }

    /**
     * Handle notification dismissed events.
     *
     * @param  NotificationDismissed  $event
     *
     * @return void
     */
    public function handleNotificationDismissed(NotificationDismissed $event)
    {
        $this->logger->logSystemEvent(
            'notification_dismissed',
            $event->notification
        );
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     *
     * @return array<string, string>
     */
    public function subscribe($events): array
    {
        return [
            Login::class => 'handleUserLogin',
            Logout::class => 'handleUserLogout',
            FileImported::class => 'handleFileImported',
            DocumentExported::class => 'handleDocumentExported',
            NotificationDismissed::class => 'handleNotificationDismissed',
        ];
    }
}
