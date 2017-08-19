<?php

namespace App\Repositories\Backend;

use App\Models\Racer;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class RacerRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = Racer::class;

    public function getRacer($racer_no)
    {
        return $this->query()
            ->where('racer_no', $racer_no)
            ->first();
    }

    public function getForDataTable()
    {
        $racers = Cache::get('racers');
        if ($racers)
            return $racers;

        $racers = $this->query()
            ->select('racer_no', 'racer_name', 'jr', 'city', 'state', 'country', 'horse_name', 'breed', 'gender', 'color',
                'horse_age', 'height', 'gps_name')
            ->orderBy('racer_name')
            ->get();

        Cache::forever('racers', $racers);
        return $racers;
    }

    public function minMaxRacerNumbers()
    {
        $minmax = Cache::get('minmax');
        if ($minmax)
            return $minmax;

        $minmax = $this->query()
            ->select(DB::raw('min(racer_no) as themin, max(racer_no) as themax'))
            ->first();

        Cache::forever('minmax', $minmax);
        return $minmax;
    }

    public function getRacerNumbers()
    {
        $racernumbers = Cache::get('racernumbers');
        if ($racernumbers)
            return $racernumbers;

        $racernumbers = $this->query()
            ->select('racer_no')
            ->orderBy('racer_no')
            ->get();

        Cache::forever('racernumbers', $racernumbers);
        return $racernumbers;
    }

    public function getRacerNames()
    {
        $racernumbers = Cache::get('racernumbers');
        if ($racernumbers)
            return $racernumbers;

        $racernumbers = $this->query()
            ->select('racer_no')
            ->orderBy('racer_no')
            ->get();

        Cache::forever('racernumbers', $racernumbers);
        return $racernumbers;
    }

    public function validateRacerNumber($racer_no)
    {
        return $this->query()
            ->where('racer_no', $racer_no)
            ->exists();
    }

}