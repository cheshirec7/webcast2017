@extends ('frontend.layouts.app')
@section ('title', 'Results by Rider')
@section('after-styles')
    <style>
        #racerimg {
            width: 100%;
            margin-bottom: 15px;
        }

        .finished {
            color: #008800;
            font-weight: bold;
        }

        .pulled {
            color: #FF0000;
            font-weight: bold;
        }

        .oncourse {
            color: #00a65a;
            font-weight: bold;
        }

        @media (max-width: 444px) {
            #btnAddToFavorites {
                margin-top: 5px;
            }
        }

        #tblTimes td + td, #tblTimes td + td + td {
            text-align: right;
        }
    </style>
@endsection
@section('page-header')

    <h1>Results by Rider
        <button id="btnRefresh" type="button" class="btn btn-success">Refresh</button>
    </h1>
    <br/>
    <form class="form-inline">
        <select id="select-racernum" class="form-control" style="display:inline-block;width:auto;">
            <option value="0">#</option>
            @foreach ($racernumbers as $racernumber)
                <option @if($racer && $racernumber->racer_no == $racer->racer_no)selected
                        @endif value="{!! $racernumber->racer_no !!}">{!! $racernumber->racer_no !!}</option>
            @endforeach
        </select>
        <select id="select-racername" class="form-control" style="display:inline-block;width:auto;">
            <option value="0">- Rider Name -</option>
            @foreach ($racernames as $racername)
                <option @if($racer && $racername->racer_no == $racer->racer_no)selected
                        @endif value="{!! $racername->racer_no !!}">{!! $racername->racer_name !!}</option>
            @endforeach
        </select>
        @if ($racer && !$isFavorite)
            <button id="btnAddToFavorites" type="button" class="btn btn-primary btn-sm">Add to Favorites</button>
        @endif
    </form>
@endsection
@section('content')
    <div class="row">
        <div class="col-xs-12 col-md-8 col-lg-5">
            @if($racer)<img id="racerimg" class="img-thumbnail img-responsive"
                            src="/img/riders/{!! $racer->racer_no !!}.JPG">
            @else<img id="racerimg" class="img-thumbnail img-responsive" src="/img/selectrider.png">
            @endif
        </div>
        <div class="col-xs-12 col-md-4 col-lg-3">
            <table class="table table-condensed table-bordered table-striped">
                <thead>
                <tr>
                    <th colspan="2">Rider Details</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td width="70">Name</td>
                    <td class="rd-numname">@if($racer){!! $racer->racer_name !!}@endif @if($racer && $racer->jr)
                            (JR)@endif @if($racer)(#{!! $racer->racer_no !!})@endif</td>
                </tr>
                <tr>
                    <td>From</td>
                    <td>@if ($racer){!! $racer->city !!}@if($racer->state)
                            , {!! $racer->state !!}@endif @if($racer->country !='US'){!! $racer->country !!}@endif @endif</td>
                </tr>
                <tr>
                    <td>Horse</td>
                    <td>@if ($racer){!! $racer->horse_name !!}@endif</td>
                </tr>
                <tr>
                    <td>Breed</td>
                    <td>@if ($racer){!! $racer->breed !!}@endif</td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td>@if ($racer){!! $racer->gender !!}@endif</td>
                </tr>
                <tr>
                    <td>Color</td>
                    <td>@if ($racer){!! $racer->color !!}@endif</td>
                </tr>
                <tr>
                    <td>Age</td>
                    <td>@if ($racer){!! $racer->horse_age !!}@endif</td>
                </tr>
                <tr>
                    <td>Height</td>
                    <td>@if ($racer){!! $racer->height !!}@endif</td>
                </tr>

                <tr>
                    <td>GPS</td>
                    <td>{!! $gps !!}</td>
                </tr>
                <tr>
                    <td>Rank</td>
                    <td>{!! $rank !!}</td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>{!! $status !!}</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="col-xs-12 col-md-12 col-lg-4">
            <table id="tblTimes" class="table table-condensed table-bordered table-striped">
                <thead>
                <tr>
                    <th width="150">Checkpoint</th>
                    <th width="80">Time IN</th>
                    <th width="80">Time OUT</th>
                    {{--<th>Pace (min/mile)</th>--}}
                    <th></th>
                </tr>
                </thead>
                <tbody>
                {!! $checktimes_table !!}
                </tbody>
            </table>
        </div>
    </div>
    <img class="img-thumbnail img-responsive" src="/img/ttpart1.jpg">
    <img class="img-thumbnail img-responsive" src="/img/ttpart2.jpg">
    <img class="img-thumbnail img-responsive" src="/img/ttpart3.jpg">
@endsection
@section('after-scripts')
    <script>
        $(function () {
            'use strict';
            let $selRacerNum = $('#select-racernum').change(function (e) {
                    window.location = '/results-by-rider/' + $selRacerNum.val();
                }),
                $selRacerName = $('#select-racername').change(function (e) {
                    window.location = '/results-by-rider/' + $selRacerName.val();
                }),
                $btnRefresh = $('#btnRefresh').click(function (e) {
                    window.location = '/results-by-rider/' + $selRacerName.val();
                }),
                $btnAddToFavorites = $('#btnAddToFavorites').click(function (e) {
                    $(this).hide();
                    $.ajax({
                        url: '/pushfavorite/' + $selRacerName.val(),
                    }).done(function (data) {
                        window.location = '/results-by-rider/' + $selRacerName.val();
                    });
                });
        });
    </script>
@endsection
