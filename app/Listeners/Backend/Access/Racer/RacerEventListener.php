<?php

namespace App\Listeners\Backend\Access\Racer;

/**
 * Class RacerEventListener.
 */
class RacerEventListener
{
    /**
     * @var string
     */
    private $history_slug = 'Racer';

    /**
     * @param $event
     */
    public function onCreated($event)
    {
        history()->withType($this->history_slug)
            ->withEntity($event->racer->id)
            ->withText('trans("history.backend.racers.created") <strong>'.$event->racer->racer_name.'</strong>')
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
            ->withEntity($event->racer->id)
            ->withText('trans("history.backend.racers.updated") <strong>'.$event->racer->racer_name.'</strong>')
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
            ->withEntity($event->racer->id)
            ->withText('trans("history.backend.racers.deleted") <strong>'.$event->racer->racer_name.'</strong>')
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
            \App\Events\Backend\Access\Racer\RacerCreated::class,
            'App\Listeners\Backend\Access\Racer\RacerEventListener@onCreated'
        );

        $events->listen(
            \App\Events\Backend\Access\Racer\RacerUpdated::class,
            'App\Listeners\Backend\Access\Racer\RacerEventListener@onUpdated'
        );

        $events->listen(
            \App\Events\Backend\Access\Racer\RacerDeleted::class,
            'App\Listeners\Backend\Access\Racer\RacerEventListener@onDeleted'
        );
    }
}
