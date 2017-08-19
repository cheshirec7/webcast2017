<?php

namespace App\Repositories\Backend;

use App\Models\Standing;
//use App\Models\Pull;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Repositories\BaseRepository;
use DateTime;

class StandingRepository extends BaseRepository
{

    /**
     * Associated Repository Model.
     */
    const MODEL = Standing::class;

    public function getStandingForRacerNo($racer_no)
    {
        return $this->query()
            ->where('racer_no', $racer_no)
            ->first();
    }

    public function getForFavoritesDataTable($ids, $pulls)
    {
        return $this->query()
            ->select('rank','racer_name','jr','the_time','checkpoint_name','status',DB::raw('null as pull_dest'),'gps_name','racer_no')
            ->whereIn('racer_no',  $ids)
            ->union($pulls)
            ->get();
    }

//update checktimes a, checkpoints b set sortorder = miles_from_start*1000 where a.checkpoint_id = b.id and check_type="IN";
//update checktimes a, checkpoints b set sortorder = miles_from_start*1000+1 where a.checkpoint_id = b.id and check_type="OUT"
    public function rebuildStandings()
    {
        Cache::forever('generating_standings', 1);

        DB::transaction(function () {

            (new Standing)->truncate();

            $sql = 'create temporary table mytemp (id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY)';
            $sql .= ' select max(checkpoint_id) as checkpoint_id, max(check_time) AS check_time, racer_no';
            $sql .= ' from checktimes';
            $sql .= ' where racer_no not in (select racer_no from pulls)';
            $sql .= ' group by racer_no';
            $sql .= ' order by max(sortorder) desc,';
            $sql .= ' max(check_time),';
            $sql .= ' max(check_time_order),';
            $sql .= ' racer_no';
            DB::statement($sql);

            $sql = 'insert into standings(racer_name,jr,racer_no,checkpoint_name,the_time,miles_from_start,gps_name)';
            $sql .= " select r.racer_name,r.jr,r.racer_no,concat(checkpoint_name, ' - ', ct.check_type),TIME_FORMAT(v.check_time,'%h:%i%p'),c.miles_from_start,r.gps_name";
            $sql .= ' from mytemp v';
            $sql .= ' inner join checktimes ct on v.racer_no = ct.racer_no and v.check_time = ct.check_time';
            $sql .= ' inner join racers r ON r.racer_no = v.racer_no';
            $sql .= ' inner join checkpoints c ON c.id = v.checkpoint_id';
            $sql .= ' order by v.id';
            DB::insert($sql);

            DB::statement('drop table mytemp');

            $race_start = getStartEventDateTime();
            $current_time = new DateTime();

            if ($current_time < $race_start)
                $this->query()
                    ->update(['status' => 'Pre-start', 'the_time' => '', 'checkpoint_name' => '']);
            else {
                $sql = "update standings set status='Finished'";
                $sql .= ' where miles_from_start=';
                $sql .= ' (select max(miles_from_start) from checkpoints where allow_in_times=1)';
                DB::update($sql);
            }
        });

        $standings = $this->query()
            ->select('rank','racer_name','jr','the_time','checkpoint_name','status','gps_name','racer_no')
            ->orderBy('rank')
            ->get();

        Cache::put('standings', $standings, 1);
        Cache::forever('standings_forever', $standings);
        Cache::forget('generating_standings');
    }

    public function getForDataTable()
    {
        if (Cache::has('standings')) {
            return Cache::get('standings');
        }

        if (Cache::has('generating_standings')) {

            if (Cache::has('standings_forever')) {
                return Cache::get('standings_forever');
            }

            return $this->query()
                ->select('rank', 'racer_name', 'jr', 'the_time', 'checkpoint_name', 'status', 'gps_name', 'racer_no')
                ->orderBy('rank')
                ->get();
        }

        $this->rebuildStandings();

        return Cache::get('standings');
    }
}
