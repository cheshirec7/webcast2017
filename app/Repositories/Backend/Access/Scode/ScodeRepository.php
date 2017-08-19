<?php

namespace App\Repositories\Backend\Access\Scode;

use App\Models\Access\Scode\Scode;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use App\Events\Backend\Access\Scode\ScodeCreated;
use App\Events\Backend\Access\Scode\ScodeDeleted;
use App\Events\Backend\Access\Scode\ScodeUpdated;
use Illuminate\Support\Facades\Cache;


class ScodeRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = Scode::class;


    public function forTimeEntry()
    {
        return $this->query()
            ->select('id', 'scode', 'description')
            ->orderBy('scode')
            ->get();
    }

    public function getForDataTable()
    {
        return $this->query()
            ->select('id', 'scode', 'description')
            ->orderBy('id');
    }

    /**
     * @param array $input
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function create(array $input)
    {
        if ($this->query()->where('scode', $input['scode'])->exists()) {
            throw new GeneralException('Status Code already exists');
        }

        if ($this->query()->where('description', $input['description'])->exists()) {
            throw new GeneralException('Description already exists');
        }

        $scode = self::MODEL;
        $scode = new $scode();

        $scode->scode = $input['scode'];
        $scode->description = $input['description'];

        try {
            if ($scode->save()) {
                event(new ScodeCreated($scode));
                Cache::flush();
                return true;
            }
        } catch (\Exception $e) {
            throw new GeneralException($e->getMessage());
        }

    }

    /**
     * @param Model $scode
     * @param  $input
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function update(Model $scode, array $input)
    {
        $scode->scode = $input['scode'];
        $scode->description = $input['description'];

        try {
            if ($scode->save()) {
                event(new ScodeUpdated($scode));
                Cache::flush();
                return true;
            }
        } catch (\Exception $e) {
            throw new GeneralException($e->getMessage());
        }
    }

    /**
     * @param Model $scode
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function delete(Model $scode)
    {

//        DB::transaction(function () use ($scode) {

        try {
            if ($scode->delete()) {
                event(new ScodeDeleted($scode));
                return true;
            }
        } catch (\Exception $e) {
            if ($e->getCode() == 23000)
                throw new GeneralException('Unable to delete. The status code is currently in use.');
            else
                throw new GeneralException($e->getMessage());
        }

        throw new GeneralException(trans('exceptions.backend.access.scodes.delete_error'));
//        });
    }

}
