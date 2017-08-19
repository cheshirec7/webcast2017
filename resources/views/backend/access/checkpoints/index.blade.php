@extends ('backend.layouts.app')

@section ('title', trans('labels.backend.access.checkpoints.management'))

@section('after-styles')
    {!! Html::style("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.css") !!}
@endsection

@section('page-header')
    <h1>{!! trans('labels.backend.access.checkpoints.management') !!}</h1>
@endsection

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{!! trans('labels.backend.access.checkpoints.management') !!}</h3>

            <div class="box-tools pull-right">
                @include('backend.access.includes.partials.checkpoint-header-buttons')
            </div>
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="table-responsive">
                <table id="checkpoints-table" class="table table-condensed table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Checkpoint</th>
                            <th>Miles From Start</th>
                            <th>Hold Time</th>
                            <th>IN Time Range</th>
                            <th>Show IN Order</th>
                            <th>OUT Time Range</th>
                            <th>Show OUT Order</th>
                            <th>Allow Pulls</th>
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
            {!! history()->renderType('checkpoint') !!}
        </div><!-- /.box-body -->
    </div><!--box box-success-->
@endsection

@section('after-scripts')
    {!! Html::script("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.js") !!}
{{--    {!! Html::script("js/dtExtend.js") !!}--}}
    <script>
        $(function() {
            $('#checkpoints-table').DataTable({
                dom: 'ti',
                processing: false,
                serverSide: false,
                autoWidth: false,
                ajax: {
                    url: '{!! route("admin.access.checkpoint.get") !!}',
                    type: 'get',
                    error: function (xhr, err) {
                        if (err === 'parsererror')
                            location.reload();
                    }
                },
                columns: [
                    {data: 'checkpoint_name', name: 'checkpoint_name'},
                    {data: 'miles_from_start', name: 'miles_from_start'},
                    {data: 'hold_time', name: 'hold_time'},
                    {data: 'in_time_range', name: 'in_time_range', sortable: false},
                    {data: 'in_time_show_ordering', name: 'in_time_show_ordering', sortable: false},
                    {data: 'out_time_range', name: 'out_time_range', sortable: false},
                    {data: 'out_time_show_ordering', name: 'out_time_show_ordering', sortable: false},
                    {data: 'allow_pulls', name: 'allow_pulls'},
                    {data: 'actions', name: 'actions', searchable: false, sortable: false}
                ],
                order: [[1, "asc"]],
                lengthMenu: [[-1], ['All']],
                language: {
                    emptyTable: 'No checkpoints found'
                }
            });
        });
    </script>
@endsection
