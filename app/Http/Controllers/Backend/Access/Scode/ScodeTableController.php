<?php

namespace App\Http\Controllers\Backend\Access\Scode;

use App\Http\Controllers\Controller;
use Yajra\Datatables\Facades\Datatables;
use App\Repositories\Backend\Access\Scode\ScodeRepository;
use App\Http\Requests\Backend\Access\Scode\ManageScodeRequest;

/**
 * Class ScodeTableController.
 */
class ScodeTableController extends Controller
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

    public function __invoke(ManageScodeRequest $request)
    {
        return Datatables::of($this->scodes->getForDataTable())
            ->escapeColumns(['scode','description'])
            ->addColumn('actions', function ($scode) {
                return $scode->action_buttons;
            })
            ->make(true);
    }
}
