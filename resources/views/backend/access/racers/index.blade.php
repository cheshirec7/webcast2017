@extends ('backend.layouts.app')

@section ('title', trans('labels.backend.access.racers.management'))

@section('after-styles')
    {!! Html::style("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.css") !!}
@endsection

@section('page-header')
    <h1>{!! trans('labels.backend.access.racers.management') !!}</h1>
@endsection

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{!! trans('labels.backend.access.racers.management') !!}</h3>

            <div class="box-tools pull-right">
                @include('backend.access.includes.partials.racer-header-buttons')
            </div>
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="table-responsive">
                <table id="racers-table" class="table table-condensed table-hover table-striped">
                    <thead>
                        <tr>
                            <th width="15">#</th>
                            <th>Rider Name</th>
                            <th>GPS Name</th>
                            <th>JR</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Country</th>
                            <th>Horse Name</th>
                            {{--<th>Breed</th>--}}
                            {{--<th>Gender</th>--}}
                            {{--<th>Color</th>--}}
                            {{--<th>Age</th>--}}
                            {{--<th>Height</th>--}}
                            <th>Award</th>
                            <th>{!! trans('labels.general.actions') !!}</th>
                        </tr>
                    </thead>
                </table>
            </div><!--table-responsive-->
        </div><!-- /.box-body -->
    </div><!--box-->

    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">{!! trans('history.backend.recent_history') !!}</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div><!-- /.box tools -->
        </div><!-- /.box-header -->
        <div class="box-body">
            {!! history()->renderType('Racer') !!}
        </div><!-- /.box-body -->
    </div><!--box box-success-->
@endsection

@section('after-scripts')
    {!! Html::script("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.js") !!}
    {!! Html::script("js/dtExtend.js") !!}
    <script>
        $(function() {
            $('#racers-table').DataTable({
                dom: 'lfrtip',
                processing: false,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: '{!! route("admin.access.racer.get") !!}',
                    type: 'post',
                    error: function (xhr, err) {
                        if (err === 'parsererror')
                            location.reload();
                    }
                },
                columns: [
                    {data: 'racer_no', name: 'racer_no'},
                    {data: 'racer_name', name: 'racer_name'},
                    {data: 'gps_name', name: 'gps_name'},
                    {data: 'jr', name: 'jr'},
                    {data: 'city', name: 'city'},
                    {data: 'state', name: 'state'},
                    {data: 'country', name: 'country'},
                    {data: 'horse_name', name: 'horse_name'},
//                    {data: 'breed', name: 'breed'},
//                    {data: 'gender', name: 'gender'},
//                    {data: 'color', name: 'color'},
//                    {data: 'horse_age', name: 'horse_age'},
//                    {data: 'height', name: 'height'},
                    {data: 'award', name: 'award'},
                    {data: 'actions', name: 'actions', searchable: false, sortable: false}
                ],
                order: [[1, "asc"]],
                language: {
                    emptyTable: 'No riders found'
                }
            });
        });
    </script>
@endsection
