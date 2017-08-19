<?php

namespace App\Repositories\Backend;

use App\Models\Checktime;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class ChecktimeRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = Checktime::class;

    public function getCheckTime($cid, $racer_no, $check_type)
    {
        return $this->query()
            ->where('checkpoint_id', $cid)
            ->where('racer_no', $racer_no)
            ->where('check_type', $check_type)
            ->first();
    }

    public function eventStarted()
    {
        return $this->query()
                ->select('id')
                ->count() > 0;
    }

    public function deleteCheckTime($cid, $racer_no, $check_type)
    {
        return $this->query()
            ->where('racer_no', $racer_no)
            ->where('checkpoint_id', $cid)
            ->where('check_type', $check_type)
            ->delete();
    }

    public function forTimeEntry($racer_no)
    {
        return $this->query()
            ->select('checkpoint_id', 'check_time', 'check_time_order')
            ->join('checkpoints as c', 'c.id', '=', 'checktimes.checkpoint_id')
            ->where('racer_no', $racer_no)
            ->orderBy('miles_from_start')
            ->get();
    }

    public function forErrorCheck()
    {
        return $this->query()
            ->select(DB::raw('checktimes.racer_no,UNIX_TIMESTAMP(check_time) AS check_time,checkpoint_name,miles_from_start'))
            ->join('racers as r', 'r.racer_no', 'checktimes.racer_no')
            ->join('checkpoints as c', 'c.id', 'checktimes.checkpoint_id')
            ->orderBy('checktimes.racer_no')
            ->orderBy('miles_from_start')
            ->orderBy('check_type')
            ->get();
    }

    public function forResultsByCheckpointDatatable($checkpoint_id, $check_type)
    {
        return $this->query()
            ->select(DB::raw("r.id as rank, racer_name, jr, TIME_FORMAT(check_time,'%h:%i%p') AS the_time, null as blank, r.racer_no"))
            ->join('racers as r', 'r.racer_no', '=', 'checktimes.racer_no')
            ->where('checkpoint_id', $checkpoint_id)
            ->where('check_type', $check_type)
            ->orderBy('check_time')
            ->orderBy('check_time_order')
            ->orderBy('racer_name')
            ->get();
    }
}
