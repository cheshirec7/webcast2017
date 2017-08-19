<?php

namespace App\Events\Backend\Access\Racer;

use Illuminate\Queue\SerializesModels;

/**
 * Class RacerUpdated.
 */
class RacerUpdated
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
