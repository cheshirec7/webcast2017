<?php

namespace App\Models\Access\Scode;

use Illuminate\Database\Eloquent\Model;
use App\Models\Access\Scode\Traits\ScodeAccess;
use App\Models\Access\Scode\Traits\Scope\ScodeScope;
use App\Models\Access\Scode\Traits\Attribute\ScodeAttribute;
use App\Models\Access\Scode\Traits\Relationship\ScodeRelationship;

/**
 * Class Scode.
 */
class Scode extends Model
{
    use ScodeScope,
        ScodeAccess,
        ScodeAttribute,
        ScodeRelationship;

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
    protected $fillable = ['scode','description'];

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'status_codes';
    }
}
