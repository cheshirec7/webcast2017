<?php

namespace App\Listeners\Backend\Access\Checkpoint;

/**
 * Class CheckpointEventListener.
 */
class CheckpointEventListener
{
    /**
     * @var string
     */
    private $history_slug = 'Checkpoint';

    /**
     * @param $event
     */
    public function onCreated($event)
    {
        history()->withType($this->history_slug)
            ->withEntity($event->checkpoint->id)
            ->withText('trans("history.backend.checkpoints.created") <strong>'.$event->checkpoint->checkpoint_name.'</strong>')
            ->withIcon('plus')
            ->withClass('bg-green')
            ->log();
    }

    /**
     * @param $event
     */
    public function onUpdated($event)
    {
        history()->withType($this->history_slug)
            ->withEntity($event->checkpoint->id)
            ->withText('trans("history.backend.checkpoints.updated") <strong>'.$event->checkpoint->checkpoint_name.'</strong>')
            ->withIcon('save')
            ->withClass('bg-aqua')
            ->log();
    }

    /**
     * @param $event
     */
    public function onDeleted($event)
    {
        history()->withType($this->history_slug)
            ->withEntity($event->checkpoint->id)
            ->withText('trans("history.backend.checkpoints.deleted") <strong>'.$event->checkpoint->checkpoint_name.'</strong>')
            ->withIcon('trash')
            ->withClass('bg-maroon')
            ->log();
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            \App\Events\Backend\Access\Checkpoint\CheckpointCreated::class,
            'App\Listeners\Backend\Access\Checkpoint\CheckpointEventListener@onCreated'
        );

        $events->listen(
            \App\Events\Backend\Access\Checkpoint\CheckpointUpdated::class,
            'App\Listeners\Backend\Access\Checkpoint\CheckpointEventListener@onUpdated'
        );

        $events->listen(
            \App\Events\Backend\Access\Checkpoint\CheckpointDeleted::class,
            'App\Listeners\Backend\Access\Checkpoint\CheckpointEventListener@onDeleted'
        );
    }
}
