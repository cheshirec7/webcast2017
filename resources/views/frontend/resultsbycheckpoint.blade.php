@extends ('frontend.layouts.app')
@section ('title', 'Results by Checkpoint')
@section('after-styles')
    {!! Html::style("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.css") !!}
@endsection
@section('page-header')
    <button id="btnRefresh1" type="button" class="btn btn-success" style="display: none;float:right;">Refresh</button>
    <h1>Results by Checkpoint</h1>
    <form class="form-inline" style="margin-top: 15px;">
        <select id="select-checkpoint" class="form-control">
            <option value="0">- Select Checkpoint -</option>
            @foreach ($checkpoints as $checkpoint)
                <option value="{!! $checkpoint->id !!}">{!! $checkpoint->checkpoint_name_type !!}</option>
            @endforeach
        </select>
    </form>
@endsection
@section('content')
    <div class="table-responsive">
        <div id="timesContainer">
            <table id="times-table" class="table table-condensed table-bordered table-striped">
                <thead>
                <tr>
                    <th width="1">Rank</th>
                    <th width="190">Rider</th>
                    <th width="1">JR</th>
                    <th width="70">Time</th>
                    <th></th>
                    <th>Rider #</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <div id="pullsContainer" style="display: none;">
            <table id="pulls-table" class="table table-condensed table-bordered table-striped">
                <thead>
                <tr>
                    <th width="180">Rider</th>
                    <th width="1">JR</th>
                    <th width="100">Status</th>
                    <th>Destination</th>
                    <th>Rider #</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
    </div>
@endsection
@section('after-scripts')
    {!! Html::script("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.js") !!}
    {!! Html::script("js/dtExtend.js") !!}
    <script>
        $(function () {
            'use strict';
            let $btnRefresh = $('#btnRefresh1').click(function (e) {
                    $selCheckpoint.trigger('change');
                }),
                $timesContainer = $('#timesContainer'),
                $pullsContainer = $('#pullsContainer'),

                $selCheckpoint = $('#select-checkpoint').change(function (e) {
                    let url = '/api/getresultsbycheckpoint/',
                        check_val = $selCheckpoint.val(),
                        check_type = $selCheckpoint.find(':selected').text();

                    if (check_val === '0') {
                        $btnRefresh.hide();
                        $checkpointTimes.clear().draw();
                        $checkpointPulls.clear().draw();
                        return;
                    }

                    $btnRefresh.show();
                    check_type = check_type.slice(check_type.indexOf('-') + 2);
                    url += check_val + '/' + check_type;
                    if (check_type === 'PULL') {
                        $checkpointPulls.ajax.url(url);
                        $checkpointPulls.order([0, 'asc']).ajax.reload(function () {
                            $checkpointTimes.clear().draw();
                            $timesContainer.hide();
                            $pullsContainer.show();
                        });
                    } else {
                        $checkpointTimes.ajax.url(url);
                        $checkpointTimes.order([0, 'asc']).ajax.reload(function () {
                            $checkpointPulls.clear().draw();
                            $pullsContainer.hide();
                            $timesContainer.show();
                        });
                    }
                }),

                $checkpointTimes = $('#times-table').DataTable({
                    dom: 'lfrtip',
                    processing: false,
                    serverSide: false,
                    autoWidth: false,
                    pagingType: 'simple',
                    columns: [
                        {
                            data: 'rank', name: 'rank',
                            render: function (data, type, full, meta) {
                                return meta.row + 1;
                            }
                        },
                        {
                            data: 'racer_name', name: 'racer_name',
                            render: function (data, type, full, meta) {
                                return '<a href="/results-by-rider/' + full.racer_no + '">' + data + ' (#' + full.racer_no + ')</a>';
                            }
                        },
                        {data: 'jr', name: 'jr'},
                        {data: 'the_time', name: 'the_time', sortable: false},
                        {data: 'blank', name: 'blank', sortable: false},
                        {data: 'racer_no', name: 'racer_no', visible: false}
                    ],
                    order: [[0, "asc"]],
                    lengthMenu: [[50, 10, 15, -1], [50, 10, 15, "All"]],
                    language: {
                        emptyTable: 'No results found'
                    },
                    searchDelay: 500
                }),

                $checkpointPulls = $('#pulls-table').DataTable({
                    dom: 'lfrtip',
                    processing: false,
                    serverSide: false,
                    autoWidth: false,
                    pagingType: 'simple',
                    columns: [
                        {
                            data: 'racer_name', name: 'racer_name',
                            render: function (data, type, full, meta) {
                                return '<a href="/results-by-rider/' + full.racer_no + '">' + data + ' (#' + full.racer_no + ')</a>';
                            }
                        },
                        {data: 'jr', name: 'jr'},
                        {data: 'description', name: 'description', sortable: false},
                        {data: 'pull_dest', name: 'pull_dest', sortable: false},
                        {data: 'racer_no', name: 'racer_no', visible: false}
                    ],
                    order: [[0, "asc"]],
                    lengthMenu: [[50, 10, 15, -1], [50, 10, 15, "All"]],
                    language: {
                        emptyTable: 'No pulls found'
                    },
                    searchDelay: 500
                });

            $selCheckpoint.trigger('change');

        });
    </script>
@endsection