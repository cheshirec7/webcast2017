@extends('backend.layouts.app')
@section('after-styles')
    <link href="{{ asset('css/timeentry.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div id="timeentry-container">
        <div id="times-display-form">
            <div class="title">Time Entry</div>
            <div class="timeentryinfo">
                <div id="racerno-label">Rider #<span class="racerno-text">- - -</span></div>
                <button data-toggle="modal" data-target="#modal-select-racer-number"
                        class="pull-right btn btn-success btn-sm">Select Rider
                </button>
            </div>
            <table class="table table-condensed table-bordered table-striped">
                <thead>
                <tr>
                    <th colspan="3">
                        <div id="radios1224">Time format:&nbsp;&nbsp;
                            <label class="radio-inline">
                                <input type="radio" name="opt1224" id="time12" value="12"
                                       @if($use_12_hour_time) checked @endif autocomplete="off">12-Hr
                            </label>

                            <label class="radio-inline">
                                <input type="radio" name="opt1224" id="time24" value="24"
                                       @if(!$use_12_hour_time) checked @endif autocomplete="off">24-Hr
                            </label>
                        </div>
                    </th>
                </tr>
                <tr>
                    <th>Chkpt ( # In / Out / Pulled )</th>
                    <th class="text-center width80">IN</th>
                    <th class="text-center width80">OUT</th>
                </tr>
                </thead>
                <tbody id="times-display-form-racer-results-body">
                </tbody>
            </table>
        </div>

        <form id="time-entry-form" class="hidden">
            <div class="title" style="margin:-5px 0 5px;">Enter Rider Time</div>
            <table class="table table-bordered">
                <tr>
                    <th style="width: 150px;">Rider #</th>
                    <td class="racerno-text text-center"></td>
                </tr>
                <tr>
                    <th>Checkpoint</th>
                    <td id="time-entry-form-chkpt" class="text-center"></td>
                </tr>
                <tr>
                    <th>IN or OUT</th>
                    <td id="time-entry-form-in-or-out" class="text-center"></td>
                </tr>
                <tr>
                    <th id="time-entry-form-1224-time-text">Time (24-hr)</th>
                    <td class="text-center">
                        <table id="time-entry-form-table-time-entry">
                            <tr>
                                <td>Hr</td>
                                <td>&nbsp;</td>
                                <td>Min</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>
                                    <button id="time-entry-form-btn-hour" tabindex="1" data-toggle="modal"
                                            data-target="#modal-hour" type="button" class="btn btn-default btn-lg">--
                                    </button>
                                </td>
                                <td>&nbsp;:&nbsp;</td>
                                <td>
                                    <button tabindex="2" data-toggle="modal" data-target="#modal-minutes"
                                            id="time-entry-form-btn-min" type="button" class="btn btn-default btn-lg">--
                                    </button>
                                </td>
                                <td id="time-entry-form-ampm"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr id="time-entry-form-show-order">
                    <th>Order <i style="font-size:11px;">(use only when two or more riders have the same time)</i></th>
                    <td>
                        <select id="time-entry-form-finish-order-select" class="form-control" tabindex="3">
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                            <option>6</option>
                            <option>7</option>
                            <option>8</option>
                            <option>9</option>
                            <option>10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th class="text-center">
                        <button id="time-entry-form-btn-delete" class="btn btn-danger btn-xs">Delete</button>&nbsp;
                        <button id="time-entry-form-btn-cancel" class="btn btn-default btn-xs">Cancel</button>
                    </th>
                    <td class="text-center">
                        <button type="submit" id="time-entry-form-btn-submit" tabindex="4" class="btn btn-success">
                            Save
                        </button>
                    </td>
                </tr>
            </table>
        </form>

        <form id="pull-racer-form" class="hidden">
            <div class="title" style="margin:-5px 0 5px;">Update Rider Status</div>
            <table class="table table-bordered">
                <tr>
                    <th>Rider #</th>
                    <td class="racerno-text text-center"></td>
                </tr>
                <tr>
                    <th>Checkpoint</th>
                    <td>
                        <select id="pull-racer-form-checkpoint-select" class="form-control" tabindex="2">
                            <option value="0">- Select -</option>
                            @foreach ($checkpoints as $checkpoint)
                                <option value="{!! $checkpoint->id !!}">[{!! $checkpoint->checkpoint_code !!}
                                    ] {!! $checkpoint->checkpoint_name !!}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>Pull Reason</th>
                    <td>
                        <select id="pull-racer-form-reason-select" class="form-control" tabindex="1">
                            <option value="0">- Select -</option>
                            @foreach ($scodes as $scode)
                                <option value="{!! $scode->id !!}">[{!! $scode->scode !!}
                                    ] {!! $scode->description !!}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>Destination</th>
                    <td>
                        <textarea id="pull-racer-form-dest-ta" class="form-control" tabindex="3" rows="3" maxlength="255"></textarea>
                    </td>
                </tr>
                <tr>
                    <th>Remarks <i>(internal use only)</i></th>
                    <td>
                        <textarea id="pull-racer-form-remarks-ta" class="form-control" tabindex="4" rows="3" maxlength="255"></textarea>
                    </td>
                </tr>
                <tr>
                    <th class="text-center">
                        <button id="pull-racer-form-btn-delete" class="btn btn-danger btn-xs">Delete</button>
                        <button id="pull-racer-form-btn-cancel" class="btn btn-default btn-xs">Cancel</button>
                    </th>
                    <td class="text-center">
                        <button type="submit" id="pull-racer-form-btn-submit" class="btn btn-success" tabindex="5">
                            Save
                        </button>
                    </td>
                </tr>
            </table>
        </form>
    </div>

    <div id="modal-select-racer-number" class="modal timeentry">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="select-racer-form">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                                    class="sr-only">Close</span></button>
                        <div class="modal-title">Enter Rider #</div>
                    </div>
                    <div class="modal-body">
                        <input type="number" id="modal-select-racer-number-control" class="form-control" step="1"
                               maxlength="3"
                               min="{!! $minmax->themin !!}" max="{!!  $minmax->themax !!}" required="required" tabindex="1"
                               value="{!! $racer_no !!}">
                        <div id="modal-select-racer-number-invalid-racer-number-msg">Invalid Rider Number</div>
                        <table id="modal-select-racer-number-keypad">
                            <tr>
                                <td>
                                    <button type="button" class="btn btn-default">7</button>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-default">8</button>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-default">9</button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <button type="button" class="btn btn-default">4</button>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-default">5</button>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-default">6</button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <button type="button" class="btn btn-default">1</button>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-default">2</button>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-default">3</button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <button type="button" class="btn btn-default" style="font-size:14px;">DEL</button>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-default">0</button>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-default" style="font-size:14px;">CLR</button>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-success" value="Load" tabindex="3">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="modal-hour" class="modal timeentry">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span
                                aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <div class="modal-title">Select Hour (24-hr)</div>
                </div>
                <div class="modal-body">
                    <table id="keypad-hour-table"></table>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-minutes" class="modal timeentry">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span
                                aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <div class="modal-title">Select Minutes</div>
                </div>
                <div class="modal-body">
                    <table id="keypad-min-table">
                        <tr>
                            <td>
                                <button type="button" class="btn btn-default">00</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default">01</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default">02</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default">03</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default">04</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default">05</button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <button type="button" class="btn btn-default">06</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default">07</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default">08</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default">09</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default">10</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default">11</button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <button type="button" class="btn btn-default">12</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default">13</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default">14</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default">15</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default">16</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default">17</button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <button type="button" class="btn btn-default">18</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default">19</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default">20</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default">21</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default">22</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default">23</button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <button type="button" class="btn btn-default">24</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default">25</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default">26</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default">27</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default">28</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default">29</button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <button type="button" class="btn btn-default">30</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default">31</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default">32</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default">33</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default">34</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default">35</button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <button type="button" class="btn btn-default">36</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default">37</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default">38</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default">39</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default">40</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default">41</button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <button type="button" class="btn btn-default">42</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default">43</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default">44</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default">45</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default">46</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default">47</button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <button type="button" class="btn btn-default">48</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default">49</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default">50</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default">51</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default">52</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default">53</button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <button type="button" class="btn btn-default">54</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default">55</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default">56</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default">57</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default">58</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-default">59</button>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('after-scripts')
    <script src="http://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script>
        window.user_id = "{!! access()->id() !!}";
    </script>
    <script src="{!! asset('/js/timeentry.js') !!}"></script>
@endsection