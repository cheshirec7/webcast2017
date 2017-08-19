<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Checktime extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'checkpoint_detail_id','racer_no','check_time','check_time_order','user_id','user_updated_id','hold_time'
    ];
}
