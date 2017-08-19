<?php

namespace App\Models\Access\Checkpoint\Traits\Scope;

/**
 * Class CheckpointScope.
 */
trait CheckpointScope
{
    /**
     * @param $query
     * @param string $direction
     *
     * @return mixed
     */
    public function scopeSort($query, $direction = 'asc')
    {
        return $query->orderBy('checkpoint_name', $direction);
    }
}
