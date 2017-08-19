<?php

namespace App\Http\Controllers;

use App\Repositories\Backend\StandingRepository;
use App\Repositories\Backend\Access\Checkpoint\CheckpointRepository;
use App\Repositories\Backend\ChecktimeRepository;
use App\Repositories\Backend\RacerRepository;
use App\Repositories\Backend\PullRepository;
use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\DB;

/**
 * Class ApiController.
 */
class ApiController extends Controller
{
    protected $standings;
    protected $checkpoints;
    protected $checktimes;
    protected $racers;
    protected $pulls;

    /**
     * Create a new controller instance.
     *
     * @param StandingRepository $standings
     * @param CheckpointRepository $checkpoints
     * @param ChecktimeRepository $checktimes
     * @param RacerRepository $racers
     * @param PullRepository $pulls
     */
    public function __construct(StandingRepository $standings,
                                CheckpointRepository $checkpoints,
                                ChecktimeRepository $checktimes,
                                RacerRepository $racers,
                                PullRepository $pulls)
    {
        $this->standings = $standings;
        $this->checkpoints = $checkpoints;
        $this->checktimes = $checktimes;
        $this->racers = $racers;
        $this->pulls = $pulls;
    }

    public function getStandings()
    {
        return Datatables::of($this->standings->getForDataTable())
            ->make(true);
    }

    public function getPulls()
    {
        return Datatables::of($this->pulls->getForDataTable())
            ->make(true);
    }

    public function getCheckpointsWithTimes($racer_no)
    {
        return $this->checkpoints->withRacerTimes($racer_no);
    }

    public function getRacers()
    {
        return Datatables::of($this->racers->getForDataTable())
            ->make(true);
    }

    public function getRacerNumbers()
    {
        return $this->racers->getRacerNumbers();
    }

    public function getUserRoles($user_id)
    {
//        \Log::debug($user);
        $sql = 'SELECT sort as checkpoint_id';
        $sql .= ' FROM role_user a';
        $sql .= ' INNER JOIN roles b ON a.role_id = b.id';
        $sql .= ' WHERE a.user_id = ' . $user_id;
        return DB::select($sql);
    }

    public function getPull($racer_no)
    {
        return $this->pulls->forTimeEntry($racer_no);
    }

    public function getResultsByCheckpoint($checkpoint_id, $checkpoint_type) {
        if ($checkpoint_type == 'PULL')
            $results = $this->pulls->forResultsByCheckpointDatatable($checkpoint_id);
        else
            $results = $this->checktimes->forResultsByCheckpointDatatable($checkpoint_id,$checkpoint_type);

        return Datatables::of($results)
            ->make(true);
    }

}
