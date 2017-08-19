<?php

namespace App\Http\Controllers\Backend\Access\Scode;

use App\Models\Access\Scode\Scode;
use App\Http\Controllers\Controller;
use App\Repositories\Backend\Access\Scode\ScodeRepository;
use App\Http\Requests\Backend\Access\Scode\StoreScodeRequest;
use App\Http\Requests\Backend\Access\Scode\ManageScodeRequest;
use App\Http\Requests\Backend\Access\Scode\UpdateScodeRequest;

/**
 * Class ScodeController.
 */
class ScodeController extends Controller
{
    /**
     * @var ScodeRepository
     */
    protected $scodes;

    /**
     * @param ScodeRepository $scodes
     */
    public function __construct(ScodeRepository $scodes)
    {
        $this->scodes = $scodes;
    }

    /**
     * @param ManageScodeRequest $request
     *
     * @return mixed
     */
    public function index(ManageScodeRequest $request)
    {
        return view('backend.access.scodes.index');
    }

    /**
     * @param ManageScodeRequest $request
     *
     * @return mixed
     */
    public function create(ManageScodeRequest $request)
    {
        return view('backend.access.scodes.create');
    }

    /**
     * @param StoreScodeRequest $request
     *
     * @return mixed
     */
    public function store(StoreScodeRequest $request)
    {
        $this->scodes->create($request->only('scode','description'));

        return redirect()->route('admin.access.scode.index')->withFlashSuccess('Status Code created');
    }

    /**
     * @param Scode $scode
     * @param ManageScodeRequest $request
     *
     * @return mixed
     */
    public function edit(Scode $scode, ManageScodeRequest $request)
    {
        return view('backend.access.scodes.edit')
            ->withScode($scode);
    }

    /**
     * @param Scode $scodes
     * @param UpdateScodeRequest $request
     *
     * @return mixed
     */
    public function update(Scode $scode, UpdateScodeRequest $request)
    {
        $this->scodes->update($scode, $request->only('scode','description'));

        return redirect()->route('admin.access.scode.index')->withFlashSuccess('Status Code updated');
    }

    /**
     * @param Scode $scodes
     * @param ManageScodeRequest $request
     *
     * @return mixed
     */
    public function destroy(Scode $scode, ManageScodeRequest $request)
    {
        $this->scodes->delete($scode);

        return redirect()->route('admin.access.scode.index')->withFlashSuccess('Status Code deleted');
    }
}
