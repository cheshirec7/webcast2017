<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ImportLog;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function importLog()
    {
        $rows = ImportLog::get();
        return view('backend.access.utilities.importlog', ['rows' => $rows]);
    }

    public function standingsReport()
    {
        $sql = 'SELECT rank,s.racer_name,s.racer_no,s.jr,the_time,r.award,r.country,s.checkpoint_name';
        $sql .= ' FROM standings s';
        $sql .= ' inner join racers r on s.racer_no = r.racer_no';

        $rows = DB::select($sql);
        return view('backend.access.utilities.standingsreport', ['rows' => $rows]);
    }
}
