<?php

namespace App\Http\Controllers\Backend\Access\Racer;

use App\Models\Access\Racer\Racer;
use App\Http\Controllers\Controller;
use App\Repositories\Backend\Access\Racer\RacerRepository;
use App\Http\Requests\Backend\Access\Racer\StoreRacerRequest;
use App\Http\Requests\Backend\Access\Racer\ManageRacerRequest;
use App\Http\Requests\Backend\Access\Racer\UpdateRacerRequest;

/**
 * Class RacerController.
 */
class RacerController extends Controller
{
    /**
     * @var RacerRepository
     */
    protected $racers;

    /**
     * @param RacerRepository       $racers
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
    public function index(ManageRacerRequest $request)
    {
        return view('backend.access.racers.index');
    }

    /**
     * @param ManageRacerRequest $request
     *
     * @return mixed
     */
    public function create(ManageRacerRequest $request)
    {
        return view('backend.access.racers.create');
    }

    /**
     * @param StoreRacerRequest $request
     *
     * @return mixed
     */
    public function store(StoreRacerRequest $request)
    {
        $this->racers->create($request->only('racer_no','racer_name','gps_name','jr','city','state','country','horse_name','breed','gender','color','horse_age','height','award'));

        return redirect()->route('admin.access.racer.index')->withFlashSuccess('Rider created');
    }

    /**
     * @param Racer              $racer
     * @param ManageRacerRequest $request
     *
     * @return mixed
     */
    public function edit(Racer $racer, ManageRacerRequest $request)
    {
        return view('backend.access.racers.edit')
            ->withRacer($racer);
    }

    /**
     * @param Racer              $racer
     * @param UpdateRacerRequest $request
     *
     * @return mixed
     */
    public function update(Racer $racer, UpdateRacerRequest $request)
    {
        $this->racers->update($racer, $request->only('racer_no','racer_name','gps_name','jr','city','state','country','horse_name','breed','gender','color','horse_age','height','award'));

        return redirect()->route('admin.access.racer.index')->withFlashSuccess('Rider updated');
    }

    /**
     * @param Racer              $racer
     * @param ManageRacerRequest $request
     *
     * @return mixed
     */
    public function destroy(Racer $racer, ManageRacerRequest $request)
    {
        $this->racers->delete($racer);

        return redirect()->route('admin.access.racer.index')->withFlashSuccess('Rider deleted');
    }
}
