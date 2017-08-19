@extends ('backend.layouts.app')

@section ('title', trans('labels.backend.access.checkpoints.management') . ' | ' . trans('labels.backend.access.checkpoints.create'))

@section('page-header')
    <h1>
        {!! trans('labels.backend.access.checkpoints.management') !!}
        <small>{!! trans('labels.backend.access.checkpoints.create') !!}</small>
    </h1>
@endsection

@section('after-styles')
    <style>
        .bar {
            font-style: italic;
            border-top: 1px dashed #ddd;
            border-bottom: 1px dashed #ddd;
            background-color: #eee;
            text-align: center;
        }
    </style>
@endsection

@section('content')
    {!! Form::open(['route' => 'admin.access.checkpoint.store', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'id' => 'create-checkpoint']) !!}

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{!! trans('labels.backend.access.checkpoints.create') !!}</h3>

            <div class="box-tools pull-right">
                @include('backend.access.includes.partials.checkpoint-header-buttons')
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="box-body">
            <div style="max-width: 400px;">
                <div class="form-group">
                    {!! Form::label('checkpoint_name', 'Checkpoint Name', ['class' => 'col-lg-5 control-label']) !!}
                    <div class="col-lg-7">
                        {!! Form::text('checkpoint_name', null, ['class' => 'form-control', 'maxlength' => '255', 'required' => 'required', 'autofocus' => 'autofocus']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('checkpoint_code', 'Code', ['class' => 'col-lg-5 control-label']) !!}
                    <div class="col-lg-7">
                        {!! Form::text('checkpoint_code', null, ['class' => 'form-control', 'maxlength' => '2', 'required' => 'required']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('miles_from_start', 'Miles From Start', ['class' => 'col-lg-5 control-label']) !!}
                    <div class="col-lg-7">
                        {!! Form::number('miles_from_start', null, ['class' => 'form-control', 'min' => '0', 'max' => '1000', 'required' => 'required']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('hold_time', 'Hold Time', ['class' => 'col-lg-5 control-label']) !!}
                    <div class="col-lg-7">
                        {!! Form::number('hold_time', 0, ['class' => 'form-control', 'min' => '0', 'max' => '120']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('allow_pulls', 'Allow Pulls', ['class' => 'col-lg-5 control-label']) !!}

                    <div class="col-lg-1">
                        {!! Form::checkbox('allow_pulls', 1, true) !!}
                    </div><!--col-lg-1-->
                </div><!--form control-->

                <td colspan="7">&nbsp;</td>
                <td colspan="7">
                    <div class="bar">&ndash; &nbsp;All times 24-hour format&nbsp; &ndash;</div>
                </td>
                <td colspan="7">&nbsp;</td>

                <table style="width:100%;">
                    <tr>
                        <td colspan="3">
                            <div class="checkbox">
                                <label>{!! Form::checkbox('allow_in_times', 1) !!} Allow IN Times</label>
                            </div>
                        </td>
                        <td style="padding:15px;"></td>
                        <td colspan="3">
                            <div class="checkbox">
                                <label>{!! Form::checkbox('in_time_show_ordering', 1) !!} Show IN Ordering</label>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="3">First IN</td>
                        <td style="padding:15px;"></td>
                        <td colspan="3">Last IN</td>
                    </tr>
                    <tr>
                        <td>
                            {!! Form::select('in_time_first_hour', $hours, null, ['class' => 'form-control']) !!}
                        </td>
                        <td style="padding: 0 5px;">:</td>
                        <td>
                            {!! Form::select('in_time_first_minute', $minutes, null, ['class' => 'form-control']) !!}
                        </td>
                        <td style="padding:15px;"></td>
                        <td>
                            {!! Form::select('in_time_last_hour', $hours, null, ['class' => 'form-control']) !!}
                        </td>
                        <td style="padding: 0 5px;">:</td>
                        <td>
                            {!! Form::select('in_time_last_minute', $minutes, null, ['class' => 'form-control']) !!}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="7">
                            <hr/>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <div class="checkbox">
                                <label>{!! Form::checkbox('allow_out_times', 1) !!} Allow OUT Times</label>
                            </div>
                        </td>
                        <td style="padding:15px;"></td>
                        <td colspan="3">
                            <div class="checkbox">
                                <label>{!! Form::checkbox('out_time_show_ordering', 1) !!} Show OUT Ordering</label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">First OUT</td>
                        <td style="padding:15px;"></td>
                        <td colspan="3">Last OUT</td>
                    </tr>
                    <tr>
                        <td>
                            {!! Form::select('out_time_first_hour', $hours, null, ['class' => 'form-control']) !!}
                        </td>
                        <td style="padding: 0 5px;">:</td>
                        <td>
                            {!! Form::select('out_time_first_minute', $minutes, null, ['class' => 'form-control']) !!}
                        </td>
                        <td style="padding:15px;"></td>
                        <td>
                            {!! Form::select('out_time_last_hour', $hours, null, ['class' => 'form-control']) !!}
                        </td>
                        <td style="padding: 0 5px;">:</td>
                        <td>
                            {!! Form::select('out_time_last_minute', $minutes, null, ['class' => 'form-control']) !!}
                        </td>
                    </tr>
                </table>
                <br/>
            </div>
        </div><!-- /.box-body -->
    </div><!--box-->

    <div class="box box-success">
        <div class="box-body">
            <div class="pull-left">
                {!! link_to_route('admin.access.checkpoint.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-xs']) !!}
            </div><!--pull-left-->

            <div class="pull-right">
                {!! Form::submit(trans('buttons.general.crud.create'), ['class' => 'btn btn-success btn-xs']) !!}
            </div><!--pull-right-->

            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->

    {!! Form::close() !!}
@endsection

@section('after-scripts')
    {!! Html::script('js/checkpoints.js') !!}
@endsection
