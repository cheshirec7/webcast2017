@extends('backend.layouts.app')
@section ('title', 'Utilities')
@section('page-header')
    <h1>Utilities</h1>
@endsection
@section('after-styles')
    <style>
        .table > tbody > tr > td,
        .table > tbody > tr > th {
            border: none;
        }
    </style>
@endsection
@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Event Start</h3>
        </div>

        <div class="box-body">
            <table class="table" style="width:450px;">
                <tbody>
                <tr>
                    <td><label for="month">Month</label></td>
                    <td><input disabled class="form-control" id="month" name="month" type="number" min="1" max="12"
                               value="{!! config('app.race_month') !!}"></td>
                    <td><label for="day">Day</label></td>
                    <td><input disabled class="form-control" id="day" name="day" type="number" min="1" max="31"
                               value="{!! config('app.race_day') !!}"></td>
                    <td><label for="year">Year</label></td>
                    <td><input disabled class="form-control" id="year" name="year" type="number" min="2017" max="2040"
                               value="{!! config('app.race_year') !!}"></td>
                </tr>
                <tr>
                    <td><label for="year">Hour</label></td>
                    <td><input disabled class="form-control" id="hour" name="hour" type="number" min="0" max="23"
                               value="{!! config('app.race_hour') !!}"></td>
                    <td><label for="year">Minute</label></td>
                    <td><input disabled class="form-control" id="minute" name="minute" type="number" min="0" max="59"
                               value="{!! config('app.race_min') !!}"></td>
                    <td></td>
                    <td></td>
                </tr>
                </tbody>
            </table>

            <br/>
            @if ($started)
                <p style="color:#009900;">The event is in progress!<br/><i>(The checktimes table must be cleared to
                        restart)</i></p>
            @else
                <br/>
                <p>
                    @if ($started)
                        {!! link_to_route('admin.access.startevent', 'Start the Event', [], ['class' => 'btn btn-default disabled']) !!}
                    @else
                        {!! link_to_route('admin.access.startevent', 'Start the Event', [], ['class' => 'btn btn-primary']) !!}
                    @endif
                </p>

                <br/>
                <p>This will:</p>
                <ul>
                    <li>Ensure that table storing times is empty</li>
                    <li>Reset In / Out / Pull aggregates</li>
                    <li>Reset the import tracking table</li>
                    <li>Add times for all Riders as having left the start at the Start Time</li>
                </ul>
            @endif
        </div>
    </div>

    <div class="box box-success">
        <div class="box-body">
            <div style="margin: 10px">
                {!! link_to_route('admin.access.flushcache', 'Flush Cache', [], ['class' => 'btn btn-success']) !!}
            </div>
        </div>
    </div>
@endsection
