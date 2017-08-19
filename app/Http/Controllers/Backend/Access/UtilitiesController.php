<?php

namespace App\Http\Controllers\Backend\Access;

use App\Http\Controllers\Controller;
use App\Repositories\Backend\ChecktimeRepository;
use App\Repositories\Backend\Access\Checkpoint\CheckpointRepository;
use Illuminate\Http\Request;
use App\Models\Standing;
use App\Models\Pull;
use App\Models\ImportLog;
use App\Models\Access\Checkpoint\Checkpoint;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class UtilitiesController extends Controller
{
    protected $checktimes;
    protected $checkpoints;

    /**
     * Create a new controller instance.
     *
     * @param ChecktimeRepository $checktimes
     * @param CheckpointRepository $checkpoints
     */
    public function __construct(
        ChecktimeRepository $checktimes,
        CheckpointRepository $checkpoints)
    {
        $this->checktimes = $checktimes;
        $this->checkpoints = $checkpoints;
    }

    /**
     * Display the utilities form.
     *
     * @param  Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $started = $this->checktimes->eventStarted();
        return view('backend.access.utilities.index', ['started' => $started]);
    }

    public function startEvent(Request $request)
    {
        if ($this->checktimes->eventStarted()) {
            return redirect()->route('admin.access.utilities')->withFlashSuccess('Event Started!');
        }

        (new Standing)->truncate();
        (new Pull)->truncate();
        (new ImportLog)->truncate();
        DB::update('UPDATE checkpoints SET num_in=0,num_out=0,num_pull=0');
        Cache::flush();

        $c = (new Checkpoint)->select('id')
            ->where('miles_from_start', 0)
            ->first();

        $dtstart = getStartEventDateTime();
        DB::insert('INSERT INTO checktimes (checkpoint_id,check_type,racer_no,user_id,check_time) SELECT ?,\'OUT\',racer_no,?,? FROM racers', [$c->id, access()->id(), $dtstart]);

        $this->checkpoints->updateInOutAggregates();

        return redirect()->route('admin.access.utilities')->withFlashSuccess('Event Started!');
    }

    public function flushCache()
    {
        Cache::flush();
        return redirect()->route('admin.access.utilities')->withFlashSuccess('Cache flushed');
    }
}
