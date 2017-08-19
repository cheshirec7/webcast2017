<?php

namespace App\Repositories\Backend\Access\Racer;

use App\Models\Access\Racer\Racer;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use App\Events\Backend\Access\Racer\RacerCreated;
use App\Events\Backend\Access\Racer\RacerDeleted;
use App\Events\Backend\Access\Racer\RacerUpdated;
use Illuminate\Support\Facades\Cache;

/**
 * Class RacerRepository.
 */
class RacerRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = Racer::class;

    /**
     * @return mixed
     */
    public function getAll()
    {
        return $this->query()
            ->orderBy('racer_no')
            ->orderBy('racer_name')
            ->get();
    }

    /**
     * @return mixed
     */
    public function getForDataTable()
    {
        return $this->query()
            ->select(['id', 'racer_no', 'racer_name', 'gps_name', 'jr', 'city', 'state', 'country', 'horse_name', 'breed', 'gender', 'color', 'horse_age', 'height', 'award']);
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
        if ($this->query()->where('racer_no', $input['racer_no'])->first()) {
            throw new GeneralException('Rider number already exists');
        }

        if ($this->query()->where('racer_name', $input['racer_name'])->first()) {
            throw new GeneralException('Rider name already exists');
        }

        DB::transaction(function () use ($input) {
            $racer = self::MODEL;
            $racer = new $racer();

            $racer->racer_name = $input['racer_name'];
            $racer->racer_no = $input['racer_no'];
            $racer->gps_name = $input['gps_name'];
            $racer->jr = $input['jr'];
            $racer->city = $input['city'];
            $racer->state = $input['state'];
            $racer->country = $input['country'];
            $racer->horse_name = $input['horse_name'];
            $racer->breed = $input['breed'];
            $racer->gender = $input['gender'];
            $racer->color = $input['color'];
            $racer->horse_age = $input['horse_age'];
            $racer->height = $input['height'];
            $racer->award = $input['award'];

            if ($racer->save()) {
                event(new RacerCreated($racer));
                Cache::flush();
                return true;
            }

            throw new GeneralException(trans('exceptions.backend.access.racers.create_error'));
        });
    }

    /**
     * @param Model $racer
     * @param  $input
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function update(Model $racer, array $input)
    {
        $racer->racer_name = $input['racer_name'];
        $racer->racer_no = $input['racer_no'];
        $racer->gps_name = $input['gps_name'];
        $racer->jr = $input['jr'];
        $racer->city = $input['city'];
        $racer->state = $input['state'];
        $racer->country = $input['country'];
        $racer->horse_name = $input['horse_name'];
        $racer->breed = $input['breed'];
        $racer->gender = $input['gender'];
        $racer->color = $input['color'];
        $racer->horse_age = $input['horse_age'];
        $racer->height = $input['height'];
        $racer->award = $input['award'];

        try {
            if ($racer->save()) {
                event(new RacerUpdated($racer));
                Cache::flush();
                return true;
            }
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                if (strpos($e->getMessage(), 'racer_no'))
                    throw new GeneralException('Unable to update, duplicate racer number found.');
                else
                    throw new GeneralException('Unable to update, duplicate racer name found.');
            } else
                throw new GeneralException($e->getMessage());
        }
    }

    /**
     * @param Model $racer
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function delete(Model $racer)
    {
//        DB::transaction(function () use ($racer) {

        try {
            if ($racer->delete()) {
                event(new RacerDeleted($racer));
                return true;
            }
        } catch (\Exception $e) {
            if ($e->getCode() == 23000)
                throw new GeneralException('Unable to delete rider. The rider has existing data in the system.');
            else
                throw new GeneralException($e->getMessage());
        }

        throw new GeneralException(trans('exceptions.backend.access.racers.delete_error'));
//        });
    }

}
