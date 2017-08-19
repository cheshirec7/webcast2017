<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repositories\Backend\PullRepository;
use App\Repositories\Backend\Access\Scode\ScodeRepository;
use App\Repositories\Backend\Access\Checkpoint\CheckpointRepository;
use App\Repositories\Backend\RacerRepository;
use Illuminate\Http\Request;
use App\Models\Pull;


class PullController extends Controller
{
    protected $pulls;
    protected $scodes;
    protected $checkpoints;
    protected $racers;

    /**
     * Create a new controller instance.
     *
     * @param  PullRepository $pulls
     * @param  ScodeRepository $scodes
     * @param  CheckpointRepository $checkpoints
     * @param  RacerRepository $racers
     */
    public function __construct(PullRepository $pulls,
                                ScodeRepository $scodes,
                                CheckpointRepository $checkpoints,
                                RacerRepository $racers)
    {
        $this->pulls = $pulls;
        $this->scodes = $scodes;
        $this->checkpoints = $checkpoints;
        $this->racers = $racers;
    }

    /**
     * Display a list of all checktimes.
     *
     * @param  Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
//        $checkpoints = $this->checkpoints->allowPullsOnly();
//        $status_codes = $this->statuscodes->forTimeEntry();
//        return view('admin.pulls.index', ['checkpoints' => $checkpoints, 'status_codes' => $status_codes]);
    }

    /**
     * Show pulls
     *
     * @param  integer $id
     * @return mixed
     */
    public function show($id)
    {
//        if ($id > 0) {
//            return $this->pulls->forTimeEntry($id);
//        } else {
////            $pulls = $this->pulls->forDatatable();
//            return Datatables::of($pulls)
//                ->make();
//        }
    }

    /**
     * Store a pull.
     *
     * @param  Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $inputs = $request->only('racer_no', 'checkpoint_id', 'status_id', 'pull_dest', 'remarks', 'pull_id');

        $racerno = intval($inputs['racer_no']);
        if ($racerno <= 0 ) {
            return response()->json(array('error' => true, 'msg' => 'Invalid racer no'));
        }

        $pull_id = intval($inputs['pull_id']);
        if ($pull_id < 0) {
            return response()->json(array('error' => true, 'msg' => 'Invalid pull id'));
        }
//        if (!$this->racers->validateRacerNumber($inputs['racer_no'])) {
//            return response()->json(array('error' => true, 'msg' => 'Invalid rider number.'));
//        }

        $pull_dest = trim(filter_var($inputs['pull_dest'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_NO_ENCODE_QUOTES));
        $remarks = trim(filter_var($inputs['remarks'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_NO_ENCODE_QUOTES));

        $pull = $this->pulls->getPull($racerno);

        if (!$pull) {
            $pull = new Pull();
            $pull->user_id = access()->id();
        } else {
            $pull->user_updated_id = access()->id();
        }

        $pull->checkpoint_id = $inputs['checkpoint_id'];
        $pull->racer_no = $racerno;
        $pull->status_id = $inputs['status_id'];
        $pull->pull_dest = $pull_dest;
        $pull->remarks = $remarks;

        if ($pull->save()) {
            $this->checkpoints->updatePullAggregate();
            return response()->json(array('error' => false));
        } else
            return response()->json(array('error' => true, 'msg' => 'Error saving'));
    }

    /**
     * Destroy the given pull.
     *
     * @param  $id
     * @return mixed
     */
    public function destroy($id)
    {
        Pull::destroy($id);
        $this->checkpoints->updatePullAggregate();
        return response()->json(array('error' => false));
    }
}
