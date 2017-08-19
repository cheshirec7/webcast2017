<?php

namespace App\Events\Backend\Access\Racer;

use Illuminate\Queue\SerializesModels;

/**
 * Class RacerDeleted.
 */
class RacerDeleted
{
    use SerializesModels;

    /**
     * @var
     */
    public $racer;

    /**
     * @param $racer
     */
    public function __construct($racer)
    {
        $this->racer = $racer;
    }
}
