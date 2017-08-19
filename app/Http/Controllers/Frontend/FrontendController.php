<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
//use App\Http\Requests\Request;
use App\Repositories\Backend\StandingRepository;
use App\Repositories\Backend\PullRepository;
use App\Repositories\Backend\RacerRepository;
use App\Repositories\Backend\Access\Checkpoint\CheckpointRepository;
use App\Repositories\Backend\ChecktimeRepository;
use Yajra\Datatables\Facades\Datatables;

/**
 * Class FrontendController.
 */
class FrontendController extends Controller
{
    protected $standings;
    protected $pulls;
    protected $racers;
    protected $checkpoints;
    protected $checktimes;
    protected $racerids_key = 'favorites.racerids';

    /**
     * Create a new controller instance.
     *
     * @param StandingRepository $standings
     * @param PullRepository $pulls
     * @param RacerRepository $racers
     * @param CheckpointRepository $checkpoints
     * @param ChecktimeRepository $checktimes
     */
    public function __construct(
        StandingRepository $standings,
        PullRepository $pulls,
        RacerRepository $racers,
        ChecktimeRepository $checktimes,
        CheckpointRepository $checkpoints)
    {
        $this->standings = $standings;
        $this->pulls = $pulls;
        $this->racers = $racers;
        $this->checkpoints = $checkpoints;
        $this->checktimes = $checktimes;
    }

    public function redirect()
    {
        return redirect('standings');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function standings()
    {
        return view('frontend.standings');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function pulls()
    {
        return view('frontend.pulls');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function checkpoints()
    {
        return view('frontend.checkpoints');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function racers()
    {
        return view('frontend.racers');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function resultsByCheckpoint()
    {
        $checkpoints = $this->checkpoints->forResultsByCheckpoint();
        return view('frontend.resultsbycheckpoint', ['checkpoints' => $checkpoints]);
    }

//    private function calcTime($last_miles_from_start,$cur_miles_from_start,$cur_time,$last_time,$checktimes,$is_out_time){
//        $hold_time = 0;
//        $last_hold_miles = 0; //avoid double counting
//        foreach($checktimes as $checktime){
//            if (($checktime->miles_from_start > $last_miles_from_start) &&
//                ($checktime->miles_from_start != $last_hold_miles)) {
//                if (($checktime->miles_from_start < $cur_miles_from_start) ||
//                    ($checktime->miles_from_start == $cur_miles_from_start && $is_out_time)){
//                    $hold_time += $checktime->hold_time;
//                    $last_hold_miles = $checktime->miles_from_start;
//                }
//            }
//            if ($checktime->miles_from_start > $cur_miles_from_start)
//                break;
//        }
//        return ($cur_time-$last_time-$hold_time*60)/60;
//    }

//    private function calcPace($do_calc,$last_miles_from_start,$cur_miles_from_start,$last_out_time,$cur_in_time,$cur_out_time_for_pace,$checktimes){
//        \Log::debug($cur_in_miles.' '.$cur_in_time.' '.$last_out_miles.' '.$last_out_time.' '.$cur_out_miles.' '.$cur_out_time);
//        if (!$do_calc)
//            return '';
//
//        if ($cur_in_time == 0 && $cur_out_time_for_pace == 0)
//            return '';
//
//        if ($last_out_time == 0)
//            return '-';
//
//        if ($cur_in_time > 0) {
//            $pace = $this->calcTime($last_miles_from_start,$cur_miles_from_start,$cur_in_time,$last_out_time,$checktimes,false)/($cur_miles_from_start-$last_miles_from_start);
//            if ($pace > 0)
//                return number_format($pace, 1);
//            return '';
//        }
//
//        $pace = $this->calcTime($last_miles_from_start,$cur_miles_from_start,$cur_out_time_for_pace,$last_out_time,$checktimes,true)/($cur_miles_from_start-$last_miles_from_start);
//        if ($pace > 0)
//            return number_format($pace, 1);
//        return '';
//    }

    private function buildEmptyChecktimesTable($checkpoints)
    {
        $res = '';
        foreach ($checkpoints as $checkpoint)
            $res .= '<tr><td>' . $checkpoint->checkpoint_name . '</td><td></td><td></td><td></td></tr>';
        return $res;
    }

    private function buildChecktimesTable($checktimes)
    {
        $last_checkpoint = '';
        $res = '';
        $last_miles_from_start = 0.0;
        $cur_miles_from_start = 0.0;
        $last_out_time = 0;
        $cur_in_time = 0;
        $cur_out_time = 0;
        $cur_out_time_for_pace = 0;
        $calc_pace = config('app.calc_pace');
        $pace = '&nbsp;';

        foreach ($checktimes as $checktime) {

            if ($last_checkpoint != $checktime->checkpoint_name) {
                if ($last_checkpoint != '') {
                    if ($cur_in_time > 0)
                        $res .= '<td>' . rtrim(date("g:ia", $cur_in_time), 'm') . '</td>';
                    else
                        $res .= '<td></td>';

                    if ($cur_out_time > 0)
                        $res .= '<td>' . rtrim(date("g:ia", $cur_out_time), 'm') . '</td>';
                    else
                        $res .= '<td></td>';

//                    $pace = $this->calcPace($calc_pace,$last_miles_from_start,$cur_miles_from_start,$last_out_time,$cur_in_time,$cur_out_time_for_pace,$checktimes);
                    $res .= '<td>' . $pace . '</td></tr>';
                    $cur_in_time = 0;
                    $cur_out_time = 0;

                    if ($cur_out_time_for_pace > 0) {
                        $last_miles_from_start = $cur_miles_from_start;
                        $last_out_time = $cur_out_time_for_pace;
                        $cur_out_time_for_pace = 0;
                    }
                }
                $res .= '<tr><td>' . $checktime->checkpoint_name . '</td>';
                $last_checkpoint = $checktime->checkpoint_name;
            }

            if ($checktime->check_time) {
                if ($checktime->check_type == 'IN') {
                    $cur_in_time = strtotime($checktime->check_time);
                    $cur_miles_from_start = $checktime->miles_from_start;
                } else if ($checktime->check_type == 'OUT') {
                    $cur_out_time = strtotime($checktime->check_time);
                    $cur_out_time_for_pace = $cur_out_time;
                    $cur_miles_from_start = $checktime->miles_from_start;
                }
            }
        }

        if ($cur_in_time > 0)
            $res .= '<td>' . rtrim(date("g:ia", $cur_in_time), 'm') . '</td>';
        else
            $res .= '<td></td>';
        $res .= '<td></td>';
//        $pace = $this->calcPace($calc_pace,$last_miles_from_start,$cur_miles_from_start,$last_out_time,$cur_in_time,$cur_out_time_for_pace,$checktimes);
        $res .= '<td>' . $pace . '</td></tr>';

        return $res;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function resultsByRacer($racer_no = 0)
    {
        $race_start = getStartEventDateTime();
        $race_end = getEndEventDateTime();
        $current_time = getCurrentDateTime();

        $status = '<span class="finished">Finished</span>'; //overwritten if pulled
        if ($current_time < $race_start)
            $status = 'Pre-Start';
        else if ($current_time < $race_end)
            $status = '<span class="oncourse">On Course</span>'; //overwritten if pulled or finished

        $racer = null;
        $rank = '';
        $checktimes_table = '';
        $gps = '';
        $racernumbers = $this->racers->getRacerNumbers();
        $racernames = $this->racers->getForDataTable();
        $racer = $this->racers->getRacer($racer_no);
        if ($racer) {
            $checktimes = $this->checkpoints->forRacerResults($racer_no);
            $checktimes_table = $this->buildChecktimesTable($checktimes);

            if ($racer->gps_name)
                $gps = '<a target="_blank" href="' . config('app.trackleaders_link') . $racer->gps_name . '">View</a>';

            $standing = $this->standings->getStandingForRacerNo($racer_no);
            if ($standing) {
                $rank = $standing->rank;
                if ($standing->status == 'Finished')
                    $status = '<span class="finished">Finished</span>';
            } else {
                $pull = $this->pulls->forRacerResults($racer_no);
                if ($pull) {
                    $status = '<span class="pulled">Pulled at ' . $pull->checkpoint_name . ' (' . $pull->reason . ')';//&nbsp;&nbsp;&nbsp;'.$pull->pull_dest;
                    if ($pull->pull_dest)
                        $status .= '&nbsp;&nbsp;&nbsp;Destination: ' . $pull->pull_dest;
                    $status .= '</span>';
                }
            }
        }

        if (!$checktimes_table) {
            $checkpoints = $this->checkpoints->inOutOnly();
            $checktimes_table = $this->buildEmptyChecktimesTable($checkpoints);
            $status = '';
        }

        return view('frontend.resultsbyracer',
            ['racernames' => $racernames,
                'racernumbers' => $racernumbers,
                'racer' => $racer,
                'status' => $status,
                'gps' => $gps,
                'rank' => $rank,
                'isFavorite' => $this->isFavorite($racer_no),
                'checktimes_table' => $checktimes_table
            ]);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function favorites()
    {
        return view('frontend.favorites');
    }

    public function getFavorites()
    {
        $racerIDs = session($this->racerids_key);
        if (!$racerIDs)
            return emptyDatatable();

        $pulls = $this->pulls->getForFavoritesDataTable($racerIDs);

        return Datatables::of($this->standings->getForFavoritesDataTable($racerIDs, $pulls))
            ->make(true);
    }

    public function clearFavorites()
    {
        session()->flush();
        return emptyDatatable();
    }

    private function isFavorite($racer_no)
    {
        $favs = session($this->racerids_key);
        $found = false;
        if ($favs) {
            foreach ($favs as $fav) {
                if ($fav == $racer_no) {
                    $found = true;
                    break;
                }
            }
        }
        return $found;
    }

    public function pushFavorite($racer_no = 0)
    {
        $racer_no = intval($racer_no);
        if ($racer_no > 0 && !$this->isFavorite($racer_no))
            session()->push($this->racerids_key, $racer_no);
    }

}
