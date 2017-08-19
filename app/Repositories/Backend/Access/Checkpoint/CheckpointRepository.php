<?php

namespace App\Repositories\Backend\Access\Checkpoint;

use App\Models\Access\Checkpoint\Checkpoint;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use App\Events\Backend\Access\Checkpoint\CheckpointCreated;
use App\Events\Backend\Access\Checkpoint\CheckpointDeleted;
use App\Events\Backend\Access\Checkpoint\CheckpointUpdated;
use Illuminate\Support\Facades\Cache;


class CheckpointRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = Checkpoint::class;

    public function allowPullsOnly()
    {
        return $this->query()
            ->select('id', 'checkpoint_name', 'checkpoint_code')
            ->where('allow_pulls', 1)
            ->orderBy('miles_from_start')
            ->get();
    }

    public function getForDataTable()
    {
        return $this->query()
            ->select('id', 'checkpoint_name', 'checkpoint_code', 'miles_from_start', 'hold_time', 'allow_in_times', 'in_time_first_hour',
                'in_time_first_minute', 'in_time_last_hour', 'in_time_last_minute', 'in_time_show_ordering', 'allow_out_times',
                'out_time_first_hour', 'out_time_first_minute', 'out_time_last_hour', 'out_time_last_minute',
                'out_time_show_ordering', 'allow_pulls')
            ->orderBy('miles_from_start');
    }

    public function forTimeEntry()
    {
        return $this->query()
            ->select('id', 'checkpoint_name', 'checkpoint_code', 'num_in', 'num_out', 'num_pull', 'allow_pulls')
            ->where('allow_in_times', 1)
            ->orWhere('allow_out_times', 1)
            ->orderBy('miles_from_start')
            ->get();
    }

    public function withRacerTimes($racer_no)
    {
        $sql = 'select c.id,checkpoint_name,checkpoint_code,miles_from_start,allow_in_times,in_time_first_hour,in_time_last_hour,';
        $sql .= ' in_time_show_ordering,allow_out_times,out_time_first_hour,out_time_last_hour,';
        $sql .= ' out_time_show_ordering,allow_pulls,num_in,num_out,num_pull,';
        $sql .= ' check_type,check_time,check_time_order';
        $sql .= ' from checkpoints c';
        $sql .= ' left join checktimes ct on ct.checkpoint_id=c.id and ct.racer_no=' . $racer_no;
        $sql .= ' where allow_in_times=1 or allow_out_times=1';//or num_pull>0
        $sql .= ' order by miles_from_start,check_type';
        return DB::select($sql);
    }

    public function forTimeEntryNoPulls()
    {
        return $this->query()
            ->where('allow_in_times', 1)
            ->orWhere('allow_out_times', 1)
            ->orderBy('miles_from_start')
            ->get();
    }

    public function inOutOnly()
    {
        return $this->query()
            ->select('id', 'checkpoint_name', 'checkpoint_code', 'num_in', 'num_out', 'num_pull')
            ->where('allow_in_times', 1)
            ->orWhere('allow_out_times', 1)
            ->orderBy('miles_from_start')
            ->get();
    }

    public function inOutOnlyForRoles($forNew)
    {
        $sql = 'select id, checkpoint_name';
        $sql .= ' from checkpoints';
        $sql .= ' where (allow_in_times=1 or allow_out_times=1)';
        if ($forNew) {
            $sql .= ' and id not in (select sort from roles)';
        }
        $sql .= ' order by miles_from_start';
        $res = DB::select($sql);
        return collect($res)->pluck('checkpoint_name', 'id');
    }

    public function forWinlink()
    {
        return $this->query()
            ->select('id', 'checkpoint_code', 'allow_in_times', 'allow_out_times', 'allow_pulls', 'miles_from_start')
            ->orderBy('miles_from_start')
            ->get();
    }

    public function forResultsByCheckpoint()
    {
//        select id,concat(checkpoint_name,' - IN'),miles_from_start,'IN' as check_type from checkpoints where allow_in_times=1
//        union
//        select id,concat(checkpoint_name,' - OUT'),miles_from_start,'OUT' as check_type from checkpoints where allow_out_times=1
//        union
//        select id,concat(checkpoint_name,' - PULL'),miles_from_start,'PULL' as check_type from checkpoints where allow_pulls=1
//        order by miles_from_start,check_type

        $sql = 'select id,concat(checkpoint_name,\' - IN\') as checkpoint_name_type,miles_from_start,\'IN\' as check_type from checkpoints where allow_in_times=1';
        $sql .= ' union';
        $sql .= ' select id,concat(checkpoint_name,\' - OUT\') as checkpoint_name_type,miles_from_start,\'OUT\' as check_type from checkpoints where allow_out_times=1';
        $sql .= ' union';
        $sql .= ' select id,concat(checkpoint_name,\' - PULL\') as checkpoint_name_type,miles_from_start,\'PULL\' as check_type from checkpoints where allow_pulls=1';
        $sql .= ' order by miles_from_start,check_type';

        return DB::select($sql);
    }


    public function forRacerResults($racer_no)
    {
        /*select checkpoint_name,check_type,TIME_FORMAT(check_time,'%h:%i%p') AS check_time,miles_from_start,check_time as ctime,hold_time
        from checkpoints c
        left join checktimes ct on ct.checkpoint_id=c.id and ct.racer_no=7
        order by miles_from_start,check_type*/

//        return $this->query()
//            ->select('checkpoint_name', 'check_type', 'miles_from_start', 'check_time', 'hold_time')
//            ->leftJoin('checktimes ct', 'ct.checkpoint_id', '=', 'posts.user_id')
//            ->where('allow_in_times', 1)
//            ->where('allow_out_times', 1)
//            ->orderBy('miles_from_start')
//            ->orderBy('check_type')
//            ->get();

        $sql = 'select checkpoint_name,check_type,miles_from_start,check_time,hold_time';
        $sql .= ' from checkpoints c';
        $sql .= ' left join checktimes ct on ct.checkpoint_id=c.id and ct.racer_no=' . $racer_no;
        $sql .= ' where allow_in_times=1 or allow_out_times=1';
        $sql .= ' order by miles_from_start,check_type';
        return DB::select($sql);
    }

    /**
     *
     */
    public function updateInOutAggregates()
    {
        /*
           UPDATE checkpoints as c,checktimes as ct
        SET c.num_in = (SELECT COUNT(ct.id) FROM checktimes ct WHERE ct.checkpoint_id = c.id AND ct.check_type='IN')
        WHERE c.id=ct.checkpoint_id
            */

        $sql = 'UPDATE checkpoints SET num_in = 0, num_out = 0';
        DB::update($sql);

        $sql = 'UPDATE checkpoints as c,checktimes as ct';
        $sql .= ' SET c.num_in = (';
        $sql .= ' SELECT COUNT(ct.id)';
        $sql .= ' FROM checktimes ct';
        $sql .= ' WHERE ct.checkpoint_id = c.id';
        $sql .= " AND ct.check_type='IN'";
        $sql .= ' )';
        $sql .= ' WHERE c.id=ct.checkpoint_id';
//        \Log::debug($sql);
        DB::update($sql);

        $sql = 'UPDATE checkpoints as c,checktimes as ct';
        $sql .= ' SET c.num_out = (';
        $sql .= ' SELECT COUNT(ct.id)';
        $sql .= ' FROM checktimes ct';
        $sql .= ' WHERE ct.checkpoint_id = c.id';
        $sql .= " AND ct.check_type='OUT'";
        $sql .= ' )';
        $sql .= ' WHERE c.id=ct.checkpoint_id';
        DB::update($sql);
    }

    public function updatePullAggregate()
    {
        $sql = 'UPDATE checkpoints SET num_pull = 0';
        DB::update($sql);

        $sql = 'UPDATE checkpoints as c,pulls as p';
        $sql .= ' SET c.num_pull = (';
        $sql .= ' SELECT COUNT(p.id)';
        $sql .= ' FROM pulls p';
        $sql .= ' WHERE p.checkpoint_id = c.id';
        $sql .= ' )';
        $sql .= ' WHERE c.id=p.checkpoint_id';
        DB::update($sql);
    }

    /**
     * @param array $input
     *
     * @throws GeneralException
     *
     * @return bool
     */
    private function validateCheckpoint(array $input)
    {
        if ($input['allow_in_times']) {

            if ((is_null($input['in_time_first_hour'])) ||
                (is_null($input['in_time_first_minute'])) ||
                (is_null($input['in_time_last_hour'])) ||
                (is_null($input['in_time_last_minute']))) {
                throw new GeneralException('Unable to save: Check IN times');
            }

            if (($input['in_time_first_hour']) == ($input['in_time_last_hour']) &&
                ($input['in_time_first_minute']) == ($input['in_time_last_minute'])) {
                throw new GeneralException('Unable to save: IN times cannot be the same');
            }
        }

        if ($input['allow_out_times']) {

            if ((is_null($input['out_time_first_hour'])) ||
                (is_null($input['out_time_first_minute'])) ||
                (is_null($input['out_time_last_hour'])) ||
                (is_null($input['out_time_last_minute']))) {
                throw new GeneralException('Unable to save: Check OUT times');
            }

            if (($input['out_time_first_hour']) == ($input['out_time_last_hour']) &&
                ($input['out_time_first_minute']) == ($input['out_time_last_minute'])) {
                throw new GeneralException('Unable to save: OUT times cannot be the same');
            }
        }
        return true;
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
        if ($this->query()->where('checkpoint_name', $input['checkpoint_name'])->exists()) {
            throw new GeneralException('Checkpoint name already exists');
        }

        if ($this->query()->where('checkpoint_code', $input['checkpoint_code'])->exists()) {
            throw new GeneralException('Checkpoint code already exists');
        }

        if ($this->query()->where('miles_from_start', $input['miles_from_start'])->exists()) {
            throw new GeneralException('Miles from start already exists');
        }

        if (!$this->validateCheckpoint($input))
            return false;

        $checkpoint = self::MODEL;
        $checkpoint = new $checkpoint();

        $checkpoint->checkpoint_name = $input['checkpoint_name'];
        $checkpoint->checkpoint_code = $input['checkpoint_code'];
        $checkpoint->miles_from_start = $input['miles_from_start'];
        $checkpoint->hold_time = 0;
        $checkpoint->allow_pulls = 0;
        $checkpoint->in_time_show_ordering = 0;
        $checkpoint->out_time_show_ordering = 0;

        if ($input['hold_time'])
            $checkpoint->hold_time = $input['hold_time'];
        if ($input['allow_pulls'])
            $checkpoint->allow_pulls = 1;
        if ($input['in_time_show_ordering'])
            $checkpoint->in_time_show_ordering = 1;
        if ($input['out_time_show_ordering'])
            $checkpoint->out_time_show_ordering = 1;

        if ($input['allow_in_times']) {
            $checkpoint->allow_in_times = 1;
            $checkpoint->in_time_first_hour = $input['in_time_first_hour'];
            $checkpoint->in_time_first_minute = $input['in_time_first_minute'];
            $checkpoint->in_time_last_hour = $input['in_time_last_hour'];
            $checkpoint->in_time_last_minute = $input['in_time_last_minute'];
        }

        if ($input['allow_out_times']) {
            $checkpoint->allow_out_times = 1;
            $checkpoint->out_time_first_hour = $input['out_time_first_hour'];
            $checkpoint->out_time_first_minute = $input['out_time_first_minute'];
            $checkpoint->out_time_last_hour = $input['out_time_last_hour'];
            $checkpoint->out_time_last_minute = $input['out_time_last_minute'];
        }

        try {
            if ($checkpoint->save()) {
                event(new CheckpointCreated($checkpoint));
                Cache::flush();
                return true;
            }
        } catch (\Exception $e) {
            throw new GeneralException($e->getMessage());
        }

    }

    /**
     * @param Model $checkpoint
     * @param  $input
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function update(Model $checkpoint, array $input)
    {
        if (!$this->validateCheckpoint($input))
            return false;

        $checkpoint->checkpoint_name = $input['checkpoint_name'];
        $checkpoint->checkpoint_code = $input['checkpoint_code'];
        $checkpoint->miles_from_start = $input['miles_from_start'];
        $checkpoint->hold_time = 0;
        $checkpoint->allow_pulls = 0;
        $checkpoint->in_time_show_ordering = 0;
        $checkpoint->out_time_show_ordering = 0;

        if ($input['hold_time'])
            $checkpoint->hold_time = $input['hold_time'];
        if ($input['allow_pulls'])
            $checkpoint->allow_pulls = 1;
        if ($input['in_time_show_ordering'])
            $checkpoint->in_time_show_ordering = 1;
        if ($input['out_time_show_ordering'])
            $checkpoint->out_time_show_ordering = 1;

        if ($input['allow_in_times']) {
            $checkpoint->allow_in_times = 1;
            $checkpoint->in_time_first_hour = $input['in_time_first_hour'];
            $checkpoint->in_time_first_minute = $input['in_time_first_minute'];
            $checkpoint->in_time_last_hour = $input['in_time_last_hour'];
            $checkpoint->in_time_last_minute = $input['in_time_last_minute'];
        }

        if ($input['allow_out_times']) {
            $checkpoint->allow_out_times = 1;
            $checkpoint->out_time_first_hour = $input['out_time_first_hour'];
            $checkpoint->out_time_first_minute = $input['out_time_first_minute'];
            $checkpoint->out_time_last_hour = $input['out_time_last_hour'];
            $checkpoint->out_time_last_minute = $input['out_time_last_minute'];
        }

        try {
            if ($checkpoint->save()) {
                event(new CheckpointUpdated($checkpoint));
                Cache::flush();
                return true;
            }
        } catch (\Exception $e) {
            throw new GeneralException($e->getMessage());
        }
    }

    /**
     * @param Model $checkpoint
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function delete(Model $checkpoint)
    {
//        DB::transaction(function () use ($checkpoint) {

        try {
            if ($checkpoint->delete()) {
                event(new CheckpointDeleted($checkpoint));
                return true;
            }
        } catch (\Exception $e) {
            if ($e->getCode() == 23000)
                throw new GeneralException('Unable to delete checkpoint. The rider has existing data in the system.');
            else
                throw new GeneralException($e->getMessage());
        }

        throw new GeneralException(trans('exceptions.backend.access.checkpoints.delete_error'));
//        });
    }

}
