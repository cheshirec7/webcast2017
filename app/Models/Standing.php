<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Standing extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'rank','racer_name','jr','racer_no','checkpoint_name','the_time','checkpoint_detail_id','status','miles_from_start'
    ];

    public $timestamps = false;
}
