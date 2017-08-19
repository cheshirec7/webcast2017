<?php

namespace App\Http\Controllers\Backend\Access\Checkpoint;

use App\Models\Access\Checkpoint\Checkpoint;
use App\Http\Controllers\Controller;
use App\Repositories\Backend\Access\Checkpoint\CheckpointRepository;
use App\Http\Requests\Backend\Access\Checkpoint\StoreCheckpointRequest;
use App\Http\Requests\Backend\Access\Checkpoint\ManageCheckpointRequest;
use App\Http\Requests\Backend\Access\Checkpoint\UpdateCheckpointRequest;

/**
 * Class CheckpointController.
 */
class CheckpointController extends Controller
{
    /**
     * @var CheckpointRepository
     */
    protected $checkpoints;

    /**
     * @param CheckpointRepository $checkpoints
     */
    public function __construct(CheckpointRepository $checkpoints)
    {
        $this->checkpoints = $checkpoints;
    }

    /**
     * @param ManageCheckpointRequest $request
     *
     * @return mixed
     */
    public function index(ManageCheckpointRequest $request)
    {
        return view('backend.access.checkpoints.index');
    }

    /**
     * @param ManageCheckpointRequest $request
     *
     * @return mixed
     */
    public function create(ManageCheckpointRequest $request)
    {
        $hours = [];
        $minutes = [];

        for ($i = 0; $i < 24; $i++) {
            array_push($hours, $i);
        }

        $minutes[] = '00';
        $minutes[15] = 15;
        $minutes[30] = 30;
        $minutes[45] = 45;

        return view('backend.access.checkpoints.create')
            ->withHours($hours)
            ->withMinutes($minutes);
    }

    /**
     * @param StoreCheckpointRequest $request
     *
     * @return mixed
     */
    public function store(StoreCheckpointRequest $request)
    {
        $this->checkpoints->create($request->only('checkpoint_name','checkpoint_code','miles_from_start','hold_time','allow_in_times','in_time_first_hour',
            'in_time_first_minute','in_time_last_hour','in_time_last_minute','in_time_show_ordering','allow_out_times',
            'out_time_first_hour','out_time_first_minute','out_time_last_hour','out_time_last_minute',
            'out_time_show_ordering','allow_pulls'));

        return redirect()->route('admin.access.checkpoint.index')->withFlashSuccess('Checkpoint created');
    }

    /**
     * @param Checkpoint $checkpoint
     * @param ManageCheckpointRequest $request
     *
     * @return mixed
     */
    public function edit(Checkpoint $checkpoint, ManageCheckpointRequest $request)
    {
        $hours = [];
        $minutes = [];

        for ($i = 0; $i < 24; $i++) {
            array_push($hours, $i);
        }

        $minutes[] = '00';
        $minutes[15] = 15;
        $minutes[30] = 30;
        $minutes[45] = 45;

        return view('backend.access.checkpoints.edit')
            ->withCheckpoint($checkpoint)
            ->withHours($hours)
            ->withMinutes($minutes);
    }

    /**
     * @param Checkpoint $checkpoint
     * @param UpdateCheckpointRequest $request
     *
     * @return mixed
     */
    public function update(Checkpoint $checkpoint, UpdateCheckpointRequest $request)
    {
        $this->checkpoints->update($checkpoint, $request->only('checkpoint_name','checkpoint_code','miles_from_start','hold_time','allow_in_times','in_time_first_hour',
            'in_time_first_minute','in_time_last_hour','in_time_last_minute','in_time_show_ordering','allow_out_times',
            'out_time_first_hour','out_time_first_minute','out_time_last_hour','out_time_last_minute',
            'out_time_show_ordering','allow_pulls'));

        return redirect()->route('admin.access.checkpoint.index')->withFlashSuccess('Checkpoint updated');
    }

    /**
     * @param Checkpoint $checkpoint
     * @param ManageCheckpointRequest $request
     *
     * @return mixed
     */
    public function destroy(Checkpoint $checkpoint, ManageCheckpointRequest $request)
    {
        $this->checkpoints->delete($checkpoint);

        return redirect()->route('admin.access.checkpoint.index')->withFlashSuccess('Checkpoint deleted');
    }
}
