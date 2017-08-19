<?php

namespace App\Models\Access\Checkpoint;

use Illuminate\Database\Eloquent\Model;
use App\Models\Access\Checkpoint\Traits\CheckpointAccess;
use App\Models\Access\Checkpoint\Traits\Scope\CheckpointScope;
use App\Models\Access\Checkpoint\Traits\Attribute\CheckpointAttribute;
use App\Models\Access\Checkpoint\Traits\Relationship\CheckpointRelationship;

/**
 * Class Checkpoint.
 */
class Checkpoint extends Model
{
    use CheckpointScope,
        CheckpointAccess,
        CheckpointAttribute,
        CheckpointRelationship;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['checkpoint_name','checkpoint_code','miles_from_start','hold_time','allow_in_times','in_time_first_hour',
        'in_time_first_minute','in_time_last_hour','in_time_last_minute','in_time_show_ordering','allow_out_times',
        'out_time_first_hour','out_time_first_minute','out_time_last_hour','out_time_last_minute',
        'out_time_show_ordering','allow_pulls','num_in','num_out','num_pull'];

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'checkpoints';
    }
}
