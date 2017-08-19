<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pull extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'checkpoint_detail_id','racer_no','status_id','remarks','pull_dest','user_id','user_updated_id'
    ];
}
