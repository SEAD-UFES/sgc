<?php

namespace App\Listeners;

use App\Events\ModelListed;
use App\Events\ModelRead;
use App\Logging\LoggerInterface;

class ModelEventSubscriber
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    /**
     * @param ModelListed $event
     *
     * @return void
     */
    public function handleModelListed(ModelListed $event): void
    {
        $this->logger->logModelEvent(
            'listed',
            $event->modelName
        );
    }

    /**
     * @param ModelRead $event
     *
     * @return void
     */
    public function handleModelRead(ModelRead $event): void
    {
        $this->logger->logModelEvent(
            'read',
            $event->model
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
            ModelListed::class => 'handleModelListed',
            ModelRead::class => 'handleModelRead',
        ];
    }
}
