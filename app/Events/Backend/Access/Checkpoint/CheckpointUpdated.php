<?php

namespace App\Events\Backend\Access\Checkpoint;

use Illuminate\Queue\SerializesModels;

/**
 * Class CheckpointUpdated.
 */
class CheckpointUpdated
{
    use SerializesModels;

    /**
     * @var
     */
    public $checkpoint;

    /**
     * @param $checkpoint
     */
    public function __construct($checkpoint)
    {
        $this->checkpoint = $checkpoint;
    }
}
