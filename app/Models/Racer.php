<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Racer extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'racer_no','racer_name','gps_name','jr','city','state','country',
      'horse_name','breed','gender','color','horse_age','height'
    ];
}
