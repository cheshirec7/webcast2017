@extends ('backend.layouts.app')

@section ('title', trans('labels.backend.access.scodes.management'))

@section('after-styles')
    {!! Html::style("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.css") !!}
@endsection

@section('page-header')
    <h1>{!! trans('labels.backend.access.scodes.management') !!}</h1>
@endsection

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{!! trans('labels.backend.access.scodes.management') !!}</h3>

            <div class="box-tools pull-right">
                @include('backend.access.includes.partials.scode-header-buttons')
            </div>
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="table-responsive">
                <table id="scodes-table" class="table table-condensed table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Description</th>
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
            {!! history()->renderType('scode') !!}
        </div><!-- /.box-body -->
    </div><!--box box-success-->
@endsection

@section('after-scripts')
    {!! Html::script("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.js") !!}
    <script>
        $(function() {
            $('#scodes-table').DataTable({
                dom: 'ti',
                processing: false,
                serverSide: false,
                autoWidth: false,
                ajax: {
                    url: '{!! route("admin.access.scode.get") !!}',
                    type: 'get',
                    error: function (xhr, err) {
                        if (err === 'parsererror')
                            location.reload();
                    }
                },
                columns: [
                    {data: 'scode', name: 'scode'},
                    {data: 'description', name: 'description'},
                    {data: 'actions', name: 'actions', searchable: false, sortable: false}
                ],
                order: [[1, "asc"]],
                lengthMenu: [[-1], ['All']],
                language: {
                    emptyTable: 'No status codes found'
                }
            });
        });
    </script>
@endsection
