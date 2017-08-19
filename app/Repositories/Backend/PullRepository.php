<?php

namespace App\Repositories\Backend;

use App\Models\Pull;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PullRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = Pull::class;

//    public function forDatatable()
//    {
//    return Pull::select("pulls.id as DT_RowId","racer_no",DB::raw("concat('[',checkpoint_code,'] ',checkpoint_name)"),"description","pull_dest","remarks","pulls.checkpoint_id","status_id")
//      ->join('checkpoints as c','c.id','=','pulls.checkpoint_id')
//      ->join('status_codes as sc','sc.id','=','pulls.status_id')
//      ->orderBy('racer_no')
//      ->get();
//    }

    public function getForFavoritesDataTable($ids)
    {
        $sql = "'DNF' as rank,racer_name,jr,'--:--' as the_time,checkpoint_name,concat('Pulled (',description,')') as status,pull_dest,gps_name,pulls.racer_no";
        return $this->query()
            ->select(DB::raw($sql))
            ->join('racers as r', 'r.racer_no', 'pulls.racer_no')
            ->join('status_codes as sc', 'sc.id', 'pulls.status_id')
            ->join('checkpoints as c', 'c.id', 'pulls.checkpoint_id')
            ->whereIn('pulls.racer_no', $ids);
    }

    public function getForDataTable()
    {
        $pulls = Cache::get('pulls');
        if ($pulls)
            return $pulls;

        if (Cache::has('pulls_generate')) {
            $pf = Cache::get('pulls_forever');
            if ($pf)
                return $pf;

            return $this->query()
                ->select('racer_name', 'jr', 'checkpoint_name', 'description as reason', 'pull_dest', 'gps_name', 'pulls.racer_no')
                ->join('checkpoints as c', 'c.id', 'pulls.checkpoint_id')
                ->join('racers as r', 'r.racer_no', 'pulls.racer_no')
                ->join('status_codes as sc', 'sc.id', 'pulls.status_id')
                ->orderBy('racer_name')
                ->get();
        }

        Cache::forever('pulls_generate', 1);

        $pulls = $this->query()
            ->select('racer_name', 'jr', 'checkpoint_name', 'description as reason', 'pull_dest', 'gps_name', 'pulls.racer_no')
            ->join('checkpoints as c', 'c.id', 'pulls.checkpoint_id')
            ->join('racers as r', 'r.racer_no', 'pulls.racer_no')
            ->join('status_codes as sc', 'sc.id', 'pulls.status_id')
            ->orderBy('racer_name')
            ->get();

        Cache::put('pulls', $pulls, 1);
        Cache::forever('pulls_forever', $pulls);
        Cache::forget('pulls_generate');

        return $pulls;
    }

    public function forTimeEntry($racer_no)
    {
        return $this->query()
            ->select('pulls.id', 'checkpoint_name', 'description', 'pull_dest', 'remarks', 'pulls.checkpoint_id', 'status_id', 'miles_from_start')
            ->join('checkpoints as c', 'c.id', 'pulls.checkpoint_id')
            ->join('status_codes as sc', 'sc.id', 'pulls.status_id')
            ->where('racer_no', $racer_no)
            ->get();
    }

    public function forErrorCheck()
    {
        return $this->query()
            ->select('racer_no', 'miles_from_start')
            ->join('checkpoints as c', 'c.id', 'pulls.checkpoint_id')
            ->get();
    }

    public function forResultsByCheckpointDatatable($checkpoint_id)
    {
        return $this->query()
            ->select('racer_name', 'jr', 'description', 'pull_dest', 'r.racer_no')
            ->join('racers as r', 'r.racer_no', '=', 'pulls.racer_no')
            ->join('status_codes as sc', 'sc.id', '=', 'pulls.status_id')
            ->where('checkpoint_id', $checkpoint_id)
            ->orderBy('racer_name')
            ->get();
    }

    public function getPull($racer_no)
    {
        return $this->query()
            ->where('racer_no', $racer_no)
            ->first();
    }

    public function forRacerResults($racer_no)
    {
        $key = 'pulls_for_racer_results_' . $racer_no;
        $pulls = Cache::get($key);
        if ($pulls)
            return $pulls;

        $pulls = $this->query()
            ->select('checkpoint_name', 'description as reason', 'pull_dest')
            ->join('checkpoints as c', 'c.id', '=', 'pulls.checkpoint_id')
            ->join('status_codes as sc', 'sc.id', '=', 'pulls.status_id')
            ->where('racer_no', $racer_no)
            ->first();

        Cache::put($key, $pulls, 1);
        return $pulls;
    }

}
