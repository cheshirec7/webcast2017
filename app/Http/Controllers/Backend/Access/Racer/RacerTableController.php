<?php

namespace App\Http\Controllers\Backend\Access\Racer;

use App\Http\Controllers\Controller;
use Yajra\Datatables\Facades\Datatables;
use App\Repositories\Backend\Access\Racer\RacerRepository;
use App\Http\Requests\Backend\Access\Racer\ManageRacerRequest;

/**
 * Class RacerTableController.
 */
class RacerTableController extends Controller
{
    /**
     * @var RacerRepository
     */
    protected $racers;

    /**
     * @param RacerRepository $racers
     */
    public function __construct(RacerRepository $racers)
    {
        $this->racers = $racers;
    }

    /**
     * @param ManageRacerRequest $request
     *
     * @return mixed
     */
    public function __invoke(ManageRacerRequest $request)
    {
        return Datatables::of($this->racers->getForDataTable())
            ->escapeColumns(['racer_name','gps_name','city','state','country','horse_name','breed','gender','color','horse_age','height','award'])
            ->addColumn('actions', function ($racer) {
                return $racer->action_buttons;
            })
            ->make(true);
    }
}
