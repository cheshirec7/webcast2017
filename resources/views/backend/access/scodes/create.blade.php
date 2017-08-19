@extends ('backend.layouts.app')

@section ('title', trans('labels.backend.access.scodes.management') . ' | ' . trans('labels.backend.access.scodes.create'))

@section('page-header')
    <h1>
        {!! trans('labels.backend.access.scodes.management') !!}
        <small>{!! trans('labels.backend.access.scodes.create') !!}</small>
    </h1>
@endsection

@section('content')
    {!! Form::open(['route' => 'admin.access.scode.store', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'id' => 'create-scode']) !!}

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{!! trans('labels.backend.access.scodes.create') !!}</h3>

            <div class="box-tools pull-right">
                @include('backend.access.includes.partials.scode-header-buttons')
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="box-body">
            <div style="max-width:400px;">
                <div class="form-group">
                    {!! Form::label('scode', 'Code', ['class' => 'col-lg-3 control-label']) !!}
                    <div class="col-lg-9">
                        {!! Form::text('scode', null, ['class' => 'form-control', 'maxlength' => '3', 'required' => 'required', 'autofocus' => 'autofocus']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('description', 'Description', ['class' => 'col-lg-3 control-label']) !!}
                    <div class="col-lg-9">
                        {!! Form::text('description', null, ['class' => 'form-control', 'maxlength' => '100', 'required' => 'required']) !!}
                    </div>
                </div>
            </div>
        </div><!-- /.box-body -->
    </div><!--box-->

    <div class="box box-success">
        <div class="box-body">
            <div class="pull-left">
                {!! link_to_route('admin.access.scode.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-xs']) !!}
            </div><!--pull-left-->

            <div class="pull-right">
                {!! Form::submit(trans('buttons.general.crud.create'), ['class' => 'btn btn-success btn-xs']) !!}
            </div><!--pull-right-->

            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->

    {!! Form::close() !!}
@endsection
