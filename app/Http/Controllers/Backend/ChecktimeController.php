<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repositories\Backend\ChecktimeRepository;
use App\Repositories\Backend\Access\Checkpoint\CheckpointRepository;
use App\Repositories\Backend\Access\Scode\ScodeRepository;
use App\Repositories\Backend\RacerRepository;
use Illuminate\Http\Request;
use App\Models\Checktime;

class ChecktimeController extends Controller
{
    protected $checktimes;
    protected $checkpoints;
    protected $scodes;
    protected $racers;

    /**
     * Create a new controller instance.
     *
     * @param ChecktimeRepository $checktimes
     * @param CheckpointRepository $checkpoints
     * @param ScodeRepository $scodes
     * @param RacerRepository $racers
     */
    public function __construct(ChecktimeRepository $checktimes,
                                CheckpointRepository $checkpoints,
                                ScodeRepository $scodes,
                                RacerRepository $racers)
    {
        $this->checktimes = $checktimes;
        $this->checkpoints = $checkpoints;
        $this->scodes = $scodes;
        $this->racers = $racers;
    }

    public function redirect(Request $request)
    {
        return redirect('admin/timeentry');
    }

    /**
     * Display a list of all checktimes.
     *
     * @param  Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $racer_no = $request->input('racer_no', 0);
        $checkpoints = $this->checkpoints->allowPullsOnly();
        $scodes = $this->scodes->forTimeEntry();
        $minmax = $this->racers->minMaxRacerNumbers();
        $use_12_hour_time = session('use_12_hour_time');

        return view('backend.timeentry', ['checkpoints' => $checkpoints,
            'scodes' => $scodes, 'minmax' => $minmax,
            'racer_no' => $racer_no, 'use_12_hour_time' => $use_12_hour_time]);
    }

    /**
     * Store a checktime.
     *
     * @param  Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        //hmm, why not store in session?
        //session(['use_12_hour_time' => $request->use_12_hour_time]);

        $inputs = $request->only('racer_no', 'cid', 'check_type', 'hour', 'min', 'check_time_order', 'use_12_hour_time', 'sortorder');

        $racerno = intval($inputs['racer_no']);
        if ($racerno <= 0 ) {
            return $this->checkpoints->withRacerTimes(0);
        }

        $checktime = $this->checktimes->getCheckTime($inputs['cid'], $racerno, $inputs['check_type']);

        if ($checktime) {
            $checktime->user_updated_id = access()->id();
        } else {
            $checktime = new Checktime();
            $checktime->user_id = access()->id();
        }

        $checktime->checkpoint_id = $inputs['cid'];
        $checktime->check_type = $inputs['check_type'];
        $checktime->racer_no = $racerno;
        $checktime->check_time_order = $inputs['check_time_order'];
        $checktime->sortorder = $inputs['sortorder'];

        $thedate = getStartEventDateTime();
        $hour = intval($inputs['hour']);
        $thedate->setTime($hour, intval($inputs['min']), 0);
        if ($hour >= 0 && $hour < 6 && $inputs['cid'] > 1)
            $thedate->modify('+1 day');
        $checktime->check_time = $thedate;

        try {
            $checktime->save();
            $this->checkpoints->updateInOutAggregates();
        } catch(\Exception $e){
            \Log::debug('');
            \Log::debug($e->getMessage());
            \Log::debug($checktime);
            \Log::debug('');
        }

        return $this->checkpoints->withRacerTimes($racerno);
    }

    /**
     * Destroy the given checktime.
     *
     * @param  $id
     * @return mixed
     */
    public function destroy($id, Request $request)
    {
        $inputs = $request->only('cid', 'check_type');
        $this->checktimes->deleteCheckTime($inputs['cid'], $id, $inputs['check_type']);
        $this->checkpoints->updateInOutAggregates();
        return $this->checkpoints->withRacerTimes($id);
    }
}
