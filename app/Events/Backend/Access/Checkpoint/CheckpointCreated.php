<?php

namespace App\Events\Backend\Access\Checkpoint;

use Illuminate\Queue\SerializesModels;

/**
 * Class CheckpointCreated.
 */
class CheckpointCreated
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
