<?php

namespace App\Events\Backend\Access\Scode;

use Illuminate\Queue\SerializesModels;

/**
 * Class ScodeDeleted.
 */
class ScodeDeleted
{
    use SerializesModels;

    /**
     * @var
     */
    public $scode;

    /**
     * @param $scode
     */
    public function __construct($scode)
    {
        $this->scode = $scode;
    }
}
