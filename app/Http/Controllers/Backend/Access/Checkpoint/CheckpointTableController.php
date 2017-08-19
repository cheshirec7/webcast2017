<?php

namespace App\Http\Controllers\Backend\Access\Checkpoint;

use App\Http\Controllers\Controller;
use Yajra\Datatables\Facades\Datatables;
use App\Repositories\Backend\Access\Checkpoint\CheckpointRepository;
use App\Http\Requests\Backend\Access\Checkpoint\ManageCheckpointRequest;

/**
 * Class CheckpointTableController.
 */
class CheckpointTableController extends Controller
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

    public function __invoke(ManageCheckpointRequest $request)
    {
        return Datatables::of($this->checkpoints->getForDataTable())
            ->escapeColumns(['checkpoint_name'])
            ->removeColumn('allow_in_times')
            ->removeColumn('allow_out_times')
            ->removeColumn('in_time_first_hour')
            ->removeColumn('in_time_last_hour')
            ->removeColumn('in_time_first_minute')
            ->removeColumn('in_time_last_minute')
            ->removeColumn('out_time_first_minute')
            ->removeColumn('out_time_last_minute')
            ->removeColumn('out_time_first_hour')
            ->removeColumn('out_time_last_hour')
            ->editColumn('checkpoint_name', function ($checkpoint) {
                return '[' . $checkpoint->checkpoint_code . '] ' . $checkpoint->checkpoint_name;
            })
            ->addColumn('in_time_range', function ($checkpoint) {
                if ($checkpoint->allow_in_times) {
                    $first_min = (string)$checkpoint->in_time_first_minute;
                    if (strlen($first_min) == 1)
                        $first_min .= '0';

                    $last_min = (string)$checkpoint->in_time_last_minute;
                    if (strlen($last_min) == 1)
                        $last_min .= '0';

                    return $checkpoint->in_time_first_hour . ':' . $first_min . '-' . $checkpoint->in_time_last_hour . ':' . $last_min;
                }
                return '';
            })
            ->addColumn('out_time_range', function ($checkpoint) {
                if ($checkpoint->allow_out_times) {
                    $first_min = (string)$checkpoint->out_time_first_minute;
                    if (strlen($first_min) == 1)
                        $first_min .= '0';

                    $last_min = (string)$checkpoint->out_time_last_minute;
                    if (strlen($last_min) == 1)
                        $last_min .= '0';

                    return $checkpoint->out_time_first_hour . ':' . $first_min . '-' . $checkpoint->out_time_last_hour . ':' . $last_min;
                }
                return '';
            })
            ->editColumn('allow_pulls', function ($checkpoint) {
                if ($checkpoint->allow_pulls)
                    return 'Yes';
                else
                    return '';
            })
            ->editColumn('hold_time', function ($checkpoint) {
                if ($checkpoint->hold_time > 0)
                    return $checkpoint->hold_time;
                else
                    return '';
            })
            ->editColumn('in_time_show_ordering', function ($checkpoint) {
                if ($checkpoint->in_time_show_ordering > 0)
                    return 'Yes';
                else
                    return '';
            })
            ->editColumn('out_time_show_ordering', function ($checkpoint) {
                if ($checkpoint->out_time_show_ordering > 0)
                    return 'Yes';
                else
                    return '';
            })
            ->addColumn('actions', function ($checkpoint) {
                return $checkpoint->action_buttons;
            })
            ->make(true);
    }
}
