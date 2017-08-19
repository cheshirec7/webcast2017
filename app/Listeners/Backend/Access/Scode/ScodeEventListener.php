<?php

namespace App\Listeners\Backend\Access\Scode;

/**
 * Class ScodeEventListener.
 */
class ScodeEventListener
{
    /**
     * @var string
     */
    private $history_slug = 'StatusCode';

    /**
     * @param $event
     */
    public function onCreated($event)
    {
        history()->withType($this->history_slug)
            ->withEntity($event->scode->id)
            ->withText('trans("history.backend.scodes.created") <strong>'.$event->scode->description.'</strong>')
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
            ->withEntity($event->scode->id)
            ->withText('trans("history.backend.scodes.updated") <strong>'.$event->scode->description.'</strong>')
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
            ->withEntity($event->scode->id)
            ->withText('trans("history.backend.scodes.deleted") <strong>'.$event->scode->description.'</strong>')
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
            \App\Events\Backend\Access\Scode\ScodeCreated::class,
            'App\Listeners\Backend\Access\Scode\ScodeEventListener@onCreated'
        );

        $events->listen(
            \App\Events\Backend\Access\Scode\ScodeUpdated::class,
            'App\Listeners\Backend\Access\Scode\ScodeEventListener@onUpdated'
        );

        $events->listen(
            \App\Events\Backend\Access\Scode\ScodeDeleted::class,
            'App\Listeners\Backend\Access\Scode\ScodeEventListener@onDeleted'
        );
    }
}
