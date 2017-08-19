@extends ('frontend.layouts.app')
@section ('title', 'Pulls')
@section('after-styles')
    {!! Html::style("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.css") !!}
@endsection
@section('page-header')
    <h1>Pulls
        <span>
            <button id="btnRefresh" type="button" class="btn btn-success">Refresh</button>
        </span>
    </h1>
@endsection
@section('content')
    <div class="table-responsive">
        <table id="pulls-table" class="table table-condensed table-hover table-striped">
            <thead>
            <tr>
                <th width="190">Rider</th>
                <th width="1">JR</th>
                <th>Checkpoint</th>
                <th>Reason</th>
                <th>Destination
                <th>GPS</th>
            </tr>
            </thead>
        </table>
        <p>&nbsp;</p><p>&nbsp;</p>
    </div>
@endsection
@section('after-scripts')
    {!! Html::script("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.js") !!}
    {!! Html::script("js/dtExtend.js") !!}
    <script>
        $(function () {
            'use strict';
            var timeStart = new Date().getTime(),
                $loading = $('.loader'),
                $btnRefresh = $('#btnRefresh').click(function (e) {

                    var curTime = new Date().getTime();

                    if (curTime - timeStart < 15000) {
                        $loading.show();
                        setInterval(function(){
                            $loading.hide();
                        },750);
                        return;
                    }

                    $pulls.order([0, 'asc']).ajax.reload();
                    timeStart = curTime;
                }),

                $pulls = $('#pulls-table').DataTable({
                    dom: 'lfrtip',
                    processing: false,
                    serverSide: false,
                    autoWidth: false,
                    pagingType: 'simple',
                    ajax: {
                        url: '{!! route("pulls.ajax.get") !!}',
                        type: 'get',
                        error: function (xhr, err) {
                            if (err === 'parsererror')
                                location.reload();
                        }
                    },
                    columns: [
                        {data: 'racer_name', name: 'rider',
                            render: function ( data, type, full, meta ) {
                                return '<a href="/results-by-rider/'+full.racer_no+'">'+data+' (#'+full.racer_no+')</a>';
                                }},
                        {data: 'jr', name: 'jr'},
                        {data: 'checkpoint_name', name: 'checkpoint_name'},
                        {data: 'reason', name: 'reason'},
                        {data: 'pull_dest', name: 'pull_dest', sortable: false},
                        {data: 'gps_name', name: 'gps_name',
                            render: function ( data, type, full, meta ) {
                            if (data)
                                return '<a target="_blank" href="{!! config('app.trackleaders_link') !!}'+data+'">View</a>';
                            else
                                return '';
                            }},
                        {data: 'racer_no', name: 'racer_no', visible: false}
                    ],
                    order: [[0, "asc"]],
                    lengthMenu: [[50, 10, 15, -1], [50, 10, 15, "All"]],
                    language: {
                        emptyTable: 'No pulls found'
                    },
                    searchDelay: 500
                });
        });
    </script>
@endsection