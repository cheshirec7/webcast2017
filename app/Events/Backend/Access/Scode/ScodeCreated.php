<?php

namespace App\Events\Backend\Access\Scode;

use Illuminate\Queue\SerializesModels;

/**
 * Class ScodeCreated.
 */
class ScodeCreated
{
    use SerializesModels;

    /**
     * @var
     */
    public $statuscode;

    /**
     * @param $scode
     */
    public function __construct($scode)
    {
        $this->scode = $scode;
    }
}
