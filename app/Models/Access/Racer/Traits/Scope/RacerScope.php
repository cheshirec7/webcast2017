<?php

namespace App\Models\Access\Racer\Traits\Scope;

/**
 * Class RacerScope.
 */
trait RacerScope
{
    /**
     * @param $query
     * @param string $direction
     *
     * @return mixed
     */
    public function scopeSort($query, $direction = 'asc')
    {
        return $query->orderBy('racer_name', $direction);
    }
}
