<?php

namespace App\Models\Access\Racer;

use Illuminate\Database\Eloquent\Model;
use App\Models\Access\Racer\Traits\RacerAccess;
use App\Models\Access\Racer\Traits\Scope\RacerScope;
use App\Models\Access\Racer\Traits\Attribute\RacerAttribute;
use App\Models\Access\Racer\Traits\Relationship\RacerRelationship;

/**
 * Class Racer.
 */
class Racer extends Model
{
    use RacerScope,
        RacerAccess,
        RacerAttribute,
        RacerRelationship;

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
    protected $fillable = ['racer_no','racer_name','gps_name','jr','city','state','country','horse_name','breed','gender','color','horse_age','height','award'];

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'racers';
    }
}
