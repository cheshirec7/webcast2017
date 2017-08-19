@extends ('frontend.layouts.app')
@section ('title', 'Riders')
@section('after-styles')
    {!! Html::style("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.css") !!}
@endsection
@section('page-header')
    <h1>Rider List</h1>
@endsection
@section('content')
    <div class="table-responsive">
        <table id="racers-table" class="table table-striped table-bordered table-condensed">
            <thead>
            <tr>
                <th>No.</th>
                <th>Rider Name</th>
                <th>JR</th>
                <th>City</th>
                <th>State</th>
                <th>Country</th>
                <th>Horse Name</th>
                <th>Breed</th>
                <th>Gender</th>
                <th>Color</th>
                <th>Age</th>
                <th>Height</th>
                <th>GPS</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
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
                var $racers = $('#racers-table').DataTable({
                    dom: 'lfrtip',
                    processing: false,
                    serverSide: false,
                    autoWidth: false,
                    pagingType: 'simple',
                    ajax: {
                        url: '{!! route("racers.ajax.get") !!}',
                        type: 'get',
                        error: function (xhr, err) {
                            if (err === 'parsererror')
                                location.reload();
                        }
                    },

                    columns: [
                        {data: 'racer_no', name: 'racer_no'},
                        {data: 'racer_name', name: 'racer_name',
                            render: function ( data, type, full, meta ) {
                                return '<a href="/results-by-rider/'+full.racer_no+'">'+data+' (#'+full.racer_no+')</a>';
                            }},
                        {data: 'jr', name: 'jr'},
                        {data: 'city', name: 'city'},
                        {data: 'state', name: 'state'},
                        {data: 'country', name: 'country'},
                        {data: 'horse_name', name: 'horse_name'},
                        {data: 'breed', name: 'breed'},
                        {data: 'gender', name: 'gender'},
                        {data: 'color', name: 'color'},
                        {data: 'horse_age', name: 'horse_age'},
                        {data: 'height', name: 'height'},
                        {data: 'gps_name', name: 'gps_name',
                            render: function ( data, type, full, meta ) {
                                if (data)
                                    return '<a target="_blank" href="{!! config('app.trackleaders_link') !!}'+data+'">View</a>';
                                else
                                    return '';
                            }}
                    ],
                    order: [[1, "asc"]],
                    lengthMenu: [[40, 10, 15, -1], [40, 10, 15, "All"]],
                    language: {
                        emptyTable: 'No racers found'
                    },
                    searchDelay: 500
                });
        });
    </script>
@endsection