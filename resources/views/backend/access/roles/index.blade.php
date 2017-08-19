@extends ('backend.layouts.app')

@section ('title', trans('labels.backend.access.roles.management'))

@section('after-styles')
    {!! Html::style("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.css") !!}
@endsection

@section('page-header')
    <h1>{!! trans('labels.backend.access.roles.management') !!}</h1>
@endsection

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{!! trans('labels.backend.access.roles.management') !!}</h3>

            <div class="box-tools pull-right">
                @include('backend.access.includes.partials.role-header-buttons')
            </div>
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="table-responsive">
                <table id="roles-table" class="table table-condensed table-hover table-striped">
                    <thead>
                        <tr>
                            <th>{!! trans('labels.backend.access.roles.table.role') !!}</th>
                            <th>{!! trans('labels.backend.access.roles.table.permissions') !!}</th>
                            <th>{!! trans('labels.backend.access.roles.table.number_of_users') !!}</th>
                            {{--<th>{!! trans('labels.backend.access.roles.table.sort') !!}</th>--}}
                            <th>Checkpoint</th>
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
            {!! history()->renderType('Role') !!}
        </div><!-- /.box-body -->
    </div><!--box box-success-->
@endsection

@section('after-scripts')
    {!! Html::script("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.js") !!}
    <script>
        $(function() {
            $('#roles-table').DataTable({
                dom: 'ti',
                processing: false,
                serverSide: false,
                autoWidth: false,
                ajax: {
                    url: '{!! route("admin.access.role.get") !!}',
                    type: 'post',
                    error: function (xhr, err) {
                        if (err === 'parsererror')
                            location.reload();
                    }
                },
                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'permissions', name: 'display_name', sortable: false},
                    {data: 'users', name: 'users', searchable: false},
//                    {data: 'sort', name: 'sort'},
                    {data: 'checkpoint_name', name: 'checkpoint_name'},
                    {data: 'actions', name: 'actions', searchable: false, sortable: false}
                ],
                order: [[0, "asc"]],
                lengthMenu: [[-1], ['All']],
                language: {
                    emptyTable: 'No roles found'
                }
            });
        });
    </script>
@endsection
