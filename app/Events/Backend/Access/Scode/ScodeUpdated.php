<?php

namespace App\Events\Backend\Access\Scode;

use Illuminate\Queue\SerializesModels;

/**
 * Class ScodeUpdated.
 */
class ScodeUpdated
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
