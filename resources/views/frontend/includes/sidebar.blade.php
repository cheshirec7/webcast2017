<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu">
            <li class="{!! active_class(Active::checkUriPattern('standings')) !!}">
                <a href="{!! route('frontend.standings') !!}">
                    <i class="fa fa-flag-checkered"></i>
                    <span>Standings</span>
                </a>
            </li>

            <li class="{!! active_class(Active::checkUriPattern('pulls')) !!}">
                <a href="{!! route('frontend.pulls') !!}">
                    <i class="fa fa-times-circle-o"></i>
                    <span>Pulls</span>
                </a>
            </li>

            <li class="{!! active_class(Active::checkUriPattern('favorites')) !!}">
                <a href="{!! route('frontend.favorites') !!}">
                    <i class="fa fa-star-o"></i>
                    <span>Favorites</span>
                </a>
            </li>

            <li class="{!! active_class(Active::checkUriPattern('results-by-rider*')) !!}">
                <a href="{!! route('frontend.resultsbyracer') !!}/0">
                    <i class="fa fa-user"></i>
                    <span>Results by Rider</span>
                </a>
            </li>

            <li class="{!! active_class(Active::checkUriPattern('results-by-checkpoint')) !!}">
                <a href="{!! route('frontend.resultsbycheckpoint') !!}">
                    <i class="fa fa-clock-o"></i>
                    <span>Results by Checkpoint</span>
                </a>
            </li>

            <li class="{!! active_class(Active::checkUriPattern('riders')) !!}">
                <a href="{!! route('frontend.racers') !!}">
                    <i class="fa fa-group"></i>
                    <span>Rider List</span>
                </a>
            </li>

            <li class="{!! active_class(Active::checkUriPattern('checkpoints')) !!}">
                <a href="{!! route('frontend.checkpoints') !!}">
                    <i class="fa fa-list-alt"></i>
                    <span>Checkpoint List</span>
                </a>
            </li>

            <li class="{!! active_class(Active::checkUriPattern('photos')) !!}">
                <a href="https://www.flickr.com/photos/teviscup/sets/" target="_blank">
                    <i class="fa fa-photo"></i>
                    <span>Photos</span>
                </a>
            </li>

            {{--<li class="{!! active_class(Active::checkUriPattern('gpslivetracking')) !!}">--}}
            {{--<a href="#">--}}
            {{--<i class="fa fa-map-marker"></i>--}}
            {{--<span>GPS Live Tracking</span>--}}
            {{--</a>--}}
            {{--</li>--}}

            <li>
                <a href="http://trackleaders.com/teviscup17f.php" target="_blank">
                    <i class="fa fa-map-marker"></i>
                    <span>GPS Live Tracking</span>
                </a>
            </li>

            @if (Auth::check())
                <hr class="sidebar-hr"/>
                <li class="{!! active_class(Active::checkUriPattern('admin/timeentry')) !!}">
                    <a href="{!! route('admin.timeentry') !!}">
                        <i class="fa fa-dashboard"></i>
                        <span>Admin Dashboard</span>
                    </a>
                </li>
                <hr class="sidebar-hr"/>
                <li>
                    <a href="{!! route('frontend.auth.logout') !!}">
                        <i class="fa fa-sign-out"></i>
                        <span>{!! trans('navs.general.logout') !!}</span>
                    </a>
                </li>
            @endif

        </ul><!-- /.sidebar-menu -->

        <hr class="sidebar-hr" style="margin-top: 15px;">
        <div id="sidebar-countdown">
            <div id="scount-line1"></div>
            <div id="scount-line2"></div>
        </div>

        <div class="sponsors menu">
            {{--<h4>Official<br />Sponsors<br /><i>of Tevis</i></h4>--}}
            @foreach ($sponsors as $sponsor)
                <a href="http://www.teviscup.org/partnerships/official-sponsors" target="_blank" {!! $sponsor !!} class="img-responsive"/></a>
            @endforeach
            <br /><br />
        </div>

    </section><!-- /.sidebar -->
</aside>