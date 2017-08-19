<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Checktime;
use App\Repositories\Backend\ChecktimeRepository;
use App\Repositories\Backend\Access\Checkpoint\CheckpointRepository;
use App\Repositories\Backend\PullRepository;
use App\Repositories\Backend\RacerRepository;
use App\Repositories\Backend\Access\Scode\ScodeRepository;
use Illuminate\Http\Request;
use App\Models\Pull;
use App\Models\ImportLog;
use DateTime;

class WinlinkController extends Controller
{
    protected $checktimes;
    protected $checkpoints;
    protected $pulls;
    protected $racers;
    protected $scodesrep;

    /**
     * Create a new controller instance.
     *
     * @param ChecktimeRepository $checktimes
     * @param CheckpointRepository $checkpoints
     * @param PullRepository $pulls
     * @param RacerRepository $racers
     * @param ScodeRepository $scodesrep
     */
    public function __construct(
        ChecktimeRepository $checktimes,
        CheckpointRepository $checkpoints,
        PullRepository $pulls,
        RacerRepository $racers,
        ScodeRepository $scodesrep)
    {
        $this->checktimes = $checktimes;
        $this->checkpoints = $checkpoints;
        $this->pulls = $pulls;
        $this->racers = $racers;
        $this->scodesrep = $scodesrep;
    }

    public function errorCheck()
    {
        $times = $this->checktimes->forErrorCheck();
        $pulls = $this->pulls->forErrorCheck();

        $racerno_save = 0;
        $checktime_save = 0;
        $miles_from_start_save = 0;
        $errs = array();
        $timecount = count($times);

        for ($idx = 0; $idx < $timecount; $idx++) {
            $time = $times[$idx];
            if ($time->racer_no == $racerno_save && $checktime_save >= $time->check_time) {
                $o = new \stdClass();
                $o->racer_no = $time->racer_no;
                $o->msg = $time->checkpoint_name . ': Check IN and OUT times';
                array_push($errs, $o);
            } else if (($time->racer_no != $racerno_save && $racerno_save != 0) || ($idx == $timecount)) {
                //only check last time for a racer for a pull
                foreach ($pulls as $key => $pull) {
                    if ($pull->racer_no == $racerno_save) {
                        if ($pull->miles_from_start < $miles_from_start_save) {
                            $o = new \stdClass();
                            $o->racer_no = $racerno_save;
                            $o->msg = 'Times entered after pull';
                            array_push($errs, $o);
                        }
                        unset($pulls[$key]);
                        break;
                    }
                }
            }
            $racerno_save = $time->racer_no;
            $checktime_save = $time->check_time;
            $miles_from_start_save = $time->miles_from_start;
        }

        return view('backend.access.utilities.errorcheck', ['errs' => $errs]);
    }

    /****************************** WINLINK ************************************/
    public function importWinlink()
    {
        return view('backend.access.utilities.winlink');
    }

    private function isWhitespace($ch)
    {
        return (($ch == " ") || ($ch == "\n") || ($ch == "\t") || ($ch == "\r") || ($ch == "\f") || ($ch == "\v"));
    }

    private function isNewline($ch)
    {
        return (($ch == "\n") || ($ch == "\t") || ($ch == "\r") || ($ch == "\f") || ($ch == "\v"));
    }

    private function validateCheckpointCode(&$oCheckpointInfo, $checkpoints)
    {
        foreach ($checkpoints as $checkpoint) {
            if ($checkpoint->checkpoint_code == $oCheckpointInfo->code)
                return true;
        }
        $oCheckpointInfo->msg = 'Invalid checkpoint code "' . $oCheckpointInfo->code . '"';
        return false;
    }

    private function validateRows(&$oCheckpointInfo, $checkpoints, $racers, $statuscodes, $scodesfordisplay)
    {
        $rows = $oCheckpointInfo->rowsARR;
        foreach ($rows as $idx => $row) {
            $rownum = $idx + 3;

            if (strlen($row->time) != 4) {
                $oCheckpointInfo->msg = 'Row ' . $rownum . ': 24-hour time must be exactly 4 characters (0200, 1500, etc) ';
                return false;
            }

            $found = false;
            foreach ($checkpoints as $checkpoint) {

                if ($checkpoint->checkpoint_code == $oCheckpointInfo->code) {
                    if (
                        ($row->inoutpull == 'IN' && $checkpoint->allow_in_times) ||
                        ($row->inoutpull == 'OUT' && $checkpoint->allow_out_times) ||
                        ($row->inoutpull == 'PULL' && $checkpoint->allow_pulls)) {
                        $row->cid = $checkpoint->id;
                        $row->miles_from_start = $checkpoint->miles_from_start;
                        $found = true;
                        break;
                    }
                }
            }
            if (!$found) {
                $oCheckpointInfo->msg = 'Line ' . $rownum . ': Checkpoint code ' . $oCheckpointInfo->code . ' / ' . $row->inoutpull . ' not found.';
                return false;
            }

            $found = false;
            foreach ($racers as $racer) {
                if ($racer->racer_no == $row->racer_no) {
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $oCheckpointInfo->msg = 'Line ' . $rownum . ': Rider # ' . $row->racer_no . ' not found.';
                return false;
            }

            if ($row->pullcode) {
                $found = false;
                foreach ($statuscodes as $statuscode) {
                    if ($statuscode->scode == $row->pullcode) {
                        $row->status_id = $statuscode->id;
                        $found = true;
                        break;
                    }
                }

                if (!$found) {
                    $oCheckpointInfo->msg = 'Line ' . $rownum . ': Invalid pull code "' . $row->pullcode . '".  Valid codes: ' . $scodesfordisplay;
                    return false;
                }
            }

        }
        return true;
    }

    private function getHeader($ta, &$oCheckpointInfo)
    {
        $i = 0;
        $inputLength = strlen($ta);

        $sBegin = '';
        while ($i < $inputLength && !$this->isWhitespace($ta[$i])) {
            $sBegin .= $ta[$i];
            $i++;
        }

        if (strtoupper($sBegin) != 'BEGIN') {
            $oCheckpointInfo->msg = 'Invalid input: No BEGIN marker.';
            return false;
        }

        $i++;
        while ($i < $inputLength && $this->isWhitespace($ta[$i]))
            $i++;

        $code = '';
        while ($i < $inputLength && !$this->isWhitespace($ta[$i])) {
            $code .= $ta[$i];
            $i++;
        }
        $oCheckpointInfo->code = strtoupper($code);

        $i++;
        while ($i < $inputLength && $this->isWhitespace($ta[$i]))
            $i++;

        //$sDate = '';
        while ($i < $inputLength && $this->isWhitespace($ta[$i])) {
            //$sDate .= $ta[$i]; //don't care about the date
            $i++;
        }

        while ($i < $inputLength && !$this->isNewline($ta[$i]))
            $i++;

        $i++;
        $oCheckpointInfo->idx = $i;
        return true;
    }

    private function getLines($ta, &$oCheckpointInfo)
    {

        $i = $oCheckpointInfo->idx;
        $rowsARR = array();
        $inputLength = strlen($ta);

        while ($i < $inputLength) {

            while ($this->isWhitespace($ta[$i]))
                $i++;

            $sLine = '';
            while ($i < $inputLength && !$this->isNewline($ta[$i])) {
                $sLine .= $ta[$i];
                $i++;
            }

            if (strtoupper($sLine) == 'END')
                break;

            $oRow = new \stdClass();
            $lineLen = strlen($sLine);
            $j = 0;

            $racer_no = '';
            while ($j < $lineLen && $sLine[$j] != "/") {
                $racer_no .= $sLine[$j];
                $j++;
            }
            $oRow->racer_no = $racer_no;

            $j++;
            $time = '';
            while ($j < $lineLen && $sLine[$j] != "/") {
                $time .= $sLine[$j];
                $j++;
            }
            $oRow->time = $time;
            $j++;

            $inoutpull = '';
            while ($j < $lineLen && $sLine[$j] != "/") {
                $inoutpull .= $sLine[$j];
                $j++;
            }
            $oRow->inoutpull = strtoupper(trim($inoutpull));

            $j++;
            $pullcode = '';
            while ($j < $lineLen && $sLine[$j] != "/") {
                $pullcode .= $sLine[$j];
                $j++;
            }
            $oRow->pullcode = strtoupper(trim($pullcode));

            $j++;
            $pulldest = '';
            while ($j < $lineLen && $sLine[$j] != "/") {
                $pulldest .= $sLine[$j];
                $j++;
            }
            $oRow->pulldest = trim($pulldest);

            $j++;
            $remarks = '';
            while ($j < $lineLen) {
                $remarks .= $sLine[$j];
                $j++;
            }
            $oRow->remarks = trim($remarks);

            $rowsARR[] = $oRow;

            $i++;
        }

        $oCheckpointInfo->rowsARR = $rowsARR;
        return true;
    }

    /*
     BEGIN LQ
          0721
          26/0122/OUT
          10/0047/IN
          10/0111/OUT
          110/0047/IN
          110/0111/OUT
          141/0108/IN
          85/0108/IN
          END
    */

    /*
     BEGIN LQ
          0720
          3/2140/IN
          3/2159/OUT
          15/2143/IN
          15/2202/PULL/LAME
          END
    */

    public function postWinlink(Request $request)
    {
        $inputs = $request->only('ta_winlink');
        $ta = trim($inputs['ta_winlink']);

        if (strlen($ta) < 25)
            return back()->withInput()->withFlashWarning('Input is not valid.');

        $checkpoints = $this->checkpoints->forWinlink();
        $racers = $this->racers->getRacerNumbers();
        $statuscodes = $this->scodesrep->forTimeEntry();

        $scodesfordisplay = '';
        foreach ($statuscodes as $sc) {
            $scodesfordisplay .= $sc->scode . ' ';
        }
        $oCheckpointInfo = new \stdClass();
        $oCheckpointInfo->msg = 'ok';
        $oCheckpointInfo->idx = 0;
        if (!$this->getHeader($ta, $oCheckpointInfo))
            return back()->withInput()->withFlashDanger($oCheckpointInfo->msg);

        if (!$this->validateCheckpointCode($oCheckpointInfo, $checkpoints))
            return back()->withInput()->withFlashDanger($oCheckpointInfo->msg);

        $this->getLines($ta, $oCheckpointInfo);

        if (!$this->validateRows($oCheckpointInfo, $checkpoints, $racers, $statuscodes, $scodesfordisplay))
            return back()->withInput()->withFlashDanger($oCheckpointInfo->msg);

        $rows = $oCheckpointInfo->rowsARR;
        if (count($rows) == 0)
            return back()->withInput()->withFlashWarning('No data');

        //save
        $importtime = new DateTime();

        $info = $oCheckpointInfo->code;
        foreach ($rows as $row) {

            if ($row->inoutpull == 'PULL') {
                $record = Pull::where('checkpoint_id', $row->cid)
                    ->where('racer_no', $row->racer_no)
                    ->first();
            } else {
                $record = Checktime::where('checkpoint_id', $row->cid)
                    ->where('racer_no', $row->racer_no)
                    ->where('check_type', $row->inoutpull)
                    ->first();
            }

            if ($record) {
                $record->user_updated_id = access()->id();
            } else {
                if ($row->inoutpull == 'PULL')
                    $record = new Pull();
                else
                    $record = new Checktime();
                $record->user_id = access()->id();
            }

            $record->racer_no = $row->racer_no;
            $record->checkpoint_id = $row->cid;

            if ($row->inoutpull == 'PULL') {
                $record->status_id = $row->status_id;
                $record->remarks = $row->remarks;
                $record->pull_dest = $row->pulldest;
            } else {
                $record->check_type = $row->inoutpull;
                $thetime = getStartEventDateTime();
                $hour = intval(substr($row->time, 0, 2));
                $min = intval(substr($row->time, -2));

                $thetime->setTime($hour, $min, 0);
                if ($hour >= 0 && $hour < 6 && $row->miles_from_start > 0)
                    $thetime->modify('+1 day');
                $record->check_time = $thetime;
            }

            try {
                $record->save();
            } catch(\Exception $e){
                \Log::debug('');
                \Log::debug($e->getMessage());
                \Log::debug($record);
                \Log::debug('');
            }

            $info .= ' ' . $row->racer_no . '/' . $row->time . '/' . $row->inoutpull;
            if ($row->pullcode)
                $info .= '/' . $row->pullcode;
            if ($row->remarks)
                $info .= '/' . $row->remarks;
            if ($row->pulldest)
                $info .= '/' . $row->pulldest;
        }

        $log = new ImportLog;
        $log->imported_data = $info;

        try {
            $log->save();
        } catch(\Exception $e){
            \Log::debug('');
            \Log::debug($e->getMessage());
            \Log::debug($info);
            \Log::debug('');
        }

        $this->checkpoints->updateInOutAggregates();
        $this->checkpoints->updatePullAggregate();

        return back()->withFlashSuccess('[' . $importtime->format('Y-m-d H:i:s') . '] Import successful: ' . $info);
    }

}
