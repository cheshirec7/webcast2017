@extends ('backend.layouts.app')

@section ('title', trans('labels.backend.access.racers.management') . ' | ' . trans('labels.backend.access.racers.create'))

@section('page-header')
    <h1>
        {!! trans('labels.backend.access.racers.management') !!}
        <small>{!! trans('labels.backend.access.racers.create') !!}</small>
    </h1>
@endsection

@section('content')
    {!! Form::open(['route' => 'admin.access.racer.store', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'id' => 'create-racer']) !!}

        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">{!! trans('labels.backend.access.racers.create') !!}</h3>

                <div class="box-tools pull-right">
                    @include('backend.access.includes.partials.racer-header-buttons')
                </div><!--box-tools pull-right-->
            </div><!-- /.box-header -->

            <div class="box-body">
                <div class="col-sm-6">

                    <div class="form-group">
                        {!! Form::label('racer_no', 'Number', ['class' => 'col-lg-2 control-label']) !!}
                        <div class="col-lg-10">
                            {!! Form::text('racer_no', null, ['class' => 'form-control', 'min'=> '1', 'max' => '999', 'required' => 'required', 'autofocus' => 'autofocus']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('racer_name', 'Name', ['class' => 'col-lg-2 control-label']) !!}
                        <div class="col-lg-10">
                            {!! Form::text('racer_name', null, ['class' => 'form-control', 'maxlength' => '64', 'required' => 'required']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('gps_name', 'GPS Name', ['class' => 'col-lg-2 control-label']) !!}
                        <div class="col-lg-10">
                            {!! Form::text('gps_name', null, ['class' => 'form-control', 'maxlength' => '100']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('jr', 'JR', ['class' => 'col-lg-2 control-label']) !!}
                        <div class="col-lg-10">
                            {!! Form::text('jr', null, ['class' => 'form-control', 'maxlength' => '2']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('city', 'City', ['class' => 'col-lg-2 control-label']) !!}
                        <div class="col-lg-10">
                            {!! Form::text('city', null, ['class' => 'form-control', 'maxlength' => '64']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('state', 'State', ['class' => 'col-lg-2 control-label']) !!}
                        <div class="col-lg-10">
                            {!! Form::text('state', null, ['class' => 'form-control', 'maxlength' => '30']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('country', 'Country', ['class' => 'col-lg-2 control-label']) !!}
                        <div class="col-lg-10">
                            {!! Form::text('country', null, ['class' => 'form-control', 'maxlength' => '30']) !!}
                        </div>
                    </div>

                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        {!! Form::label('horse_name', 'Horse Name', ['class' => 'col-lg-2 control-label']) !!}
                        <div class="col-lg-10">
                            {!! Form::text('horse_name', null, ['class' => 'form-control', 'maxlength' => '64']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('breed', 'Breed', ['class' => 'col-lg-2 control-label']) !!}
                        <div class="col-lg-10">
                            {!! Form::text('breed', null, ['class' => 'form-control', 'maxlength' => '30']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('gender', 'Gender', ['class' => 'col-lg-2 control-label']) !!}
                        <div class="col-lg-10">
                            {!! Form::text('gender', null, ['class' => 'form-control', 'maxlength' => '1']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('color', 'Color', ['class' => 'col-lg-2 control-label']) !!}
                        <div class="col-lg-10">
                            {!! Form::text('color', null, ['class' => 'form-control', 'maxlength' => '20']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('horse_age', 'Age', ['class' => 'col-lg-2 control-label']) !!}
                        <div class="col-lg-10">
                            {!! Form::text('horse_age', null, ['class' => 'form-control', 'maxlength' => '5']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('height', 'Height', ['class' => 'col-lg-2 control-label']) !!}
                        <div class="col-lg-10">
                            {!! Form::text('height', null, ['class' => 'form-control', 'maxlength' => '10']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('award', 'Award', ['class' => 'col-lg-2 control-label']) !!}
                        <div class="col-lg-10">
                            {!! Form::text('award', null, ['class' => 'form-control', 'maxlength' => '100']) !!}
                        </div>
                    </div>

                </div>
            </div><!-- /.box-body -->
        </div><!--box-->

        <div class="box box-success">
            <div class="box-body">
                <div class="pull-left">
                    {!! link_to_route('admin.access.racer.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-xs']) !!}
                </div><!--pull-left-->

                <div class="pull-right">
                    {!! Form::submit(trans('buttons.general.crud.create'), ['class' => 'btn btn-success btn-xs']) !!}
                </div><!--pull-right-->

                <div class="clearfix"></div>
            </div><!-- /.box-body -->
        </div><!--box-->

    {!! Form::close() !!}
@endsection

{{--@section('after-scripts')--}}
    {{--{!! Html::script('js/backend/access/racers/script.js') !!}--}}
{{--@endsection--}}
