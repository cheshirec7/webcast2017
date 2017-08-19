<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{!! access()->user()->picture !!}" class="img-circle" alt="User Image"/>
            </div><!--pull-left-->
            <div class="pull-left info">
                <p>{{ access()->user()->full_name }}</p>
                <!-- Status -->
                <a href="#"><i
                            class="fa fa-circle text-success"></i> {!! trans('strings.backend.general.status.online') !!}
                </a>
            </div><!--pull-left-->
        </div><!--user-panel-->

        <ul class="sidebar-menu">
            {{--            <li class="header">{!! trans('menus.backend.sidebar.general') !!}</li>--}}

            <li class="{!! active_class(Active::checkUriPattern('admin/timeentry')) !!}">
                <a href="{!! route('admin.timeentry') !!}">
                    <i class="fa fa-clock-o"></i>
                    <span>Time Entry</span>
                </a>
            </li>

            @role('Administrator')
            <li class="{!! active_class(Active::checkUriPattern('admin/winlink')) !!}">
                <a href="{!! route('admin.importwinlink') !!}">
                    <i class="fa fa-upload"></i>
                    <span>Import Winlink</span>
                </a>
            </li>

            <li class="{!! active_class(Active::checkUriPattern('admin/errorcheck')) !!}">
                <a href="{!! route('admin.errorcheck') !!}">
                    <i class="fa fa-check-square-o"></i>
                    <span>Error Check</span>
                </a>
            </li>

            <li class="{!! active_class(Active::checkUriPattern('admin/reports*')) !!} treeview">
                <a href="#">
                    <i class="fa fa-list-alt"></i>
                    <span>Reports</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu {!! active_class(Active::checkUriPattern('admin/reports*'), 'menu-open') !!}"
                    style="display: none; {!! active_class(Active::checkUriPattern('admin/reports*'), 'display: block;') !!}">
                    <li class="{!! active_class(Active::checkUriPattern('admin/reports/standingsreport')) !!}">
                        <a href="{!! route('admin.reports.standingsreport') !!}">
                            <i class="fa fa-circle-o"></i>
                            <span>Current Standings</span>
                        </a>
                    </li>

                    <li class="{!! active_class(Active::checkUriPattern('admin/reports/importlog')) !!}">
                        <a href="{!! route('admin.reports.importlog') !!}">
                            <i class="fa fa-circle-o"></i>
                            <span>Import Log</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="{!! active_class(Active::checkUriPattern('admin/access/*')) !!} treeview">
                <a href="#">
                    <i class="fa fa-gear"></i>
                    <span>System Management</span>

                    @if ($pending_approval > 0)
                        <span class="label label-danger pull-right">{!! $pending_approval !!}</span>
                    @else
                        <i class="fa fa-angle-left pull-right"></i>
                    @endif
                </a>

                <ul class="treeview-menu {!! active_class(Active::checkUriPattern('admin/access/*'), 'menu-open') !!}"
                    style="display: none; {!! active_class(Active::checkUriPattern('admin/access/*'), 'display: block;') !!}">
                    <li class="{!! active_class(Active::checkUriPattern('admin/access/utilities')) !!}">
                        <a href="{!! route('admin.access.utilities') !!}">
                            <i class="fa fa-circle-o"></i>
                            <span>Utilities</span>
                        </a>
                    </li>
                    <li class="{!! active_class(Active::checkUriPattern('admin/access/role*')) !!}">
                        <a href="{!! route('admin.access.role.index') !!}">
                            <i class="fa fa-circle-o"></i>
                            <span>Roles</span>
                        </a>
                    </li>
                    <li class="{!! active_class(Active::checkUriPattern('admin/access/user*')) !!}">
                        <a href="{!! route('admin.access.user.index') !!}">
                            <i class="fa fa-circle-o"></i>
                            <span>Users</span>

                            @if ($pending_approval > 0)
                                <span class="label label-danger pull-right">{!! $pending_approval !!}</span>
                            @endif
                        </a>
                    </li>
                    <li class="{!! active_class(Active::checkUriPattern('admin/access/racer*')) !!}">
                        <a href="{!! route('admin.access.racer.index') !!}">
                            <i class="fa fa-circle-o"></i>
                            <span>Riders</span>
                        </a>
                    </li>
                    <li class="{!! active_class(Active::checkUriPattern('admin/access/checkpoint*')) !!}">
                        <a href="{!! route('admin.access.checkpoint.index') !!}">
                            <i class="fa fa-circle-o"></i>
                            <span>Checkpoints</span>
                        </a>
                    </li>
                    <li class="{!! active_class(Active::checkUriPattern('admin/access/scode*')) !!}">
                        <a href="{!! route('admin.access.scode.index') !!}">
                            <i class="fa fa-circle-o"></i>
                            <span>Status Codes</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="{!! active_class(Active::checkUriPattern('admin/log-viewer*')) !!} treeview">
                <a href="#">
                    <i class="fa fa-list"></i>
                    <span>{!! trans('menus.backend.log-viewer.main') !!}</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu {!! active_class(Active::checkUriPattern('admin/log-viewer*'), 'menu-open') !!}"
                    style="display: none; {!! active_class(Active::checkUriPattern('admin/log-viewer*'), 'display: block;') !!}">
                    <li class="{!! active_class(Active::checkUriPattern('admin/log-viewer')) !!}">
                        <a href="{!! route('log-viewer::dashboard') !!}">
                            <i class="fa fa-circle-o"></i>
                            <span>{!! trans('menus.backend.log-viewer.dashboard') !!}</span>
                        </a>
                    </li>

                    <li class="{!! active_class(Active::checkUriPattern('admin/log-viewer/logs')) !!}">
                        <a href="{!! route('log-viewer::logs.list') !!}">
                            <i class="fa fa-circle-o"></i>
                            <span>{!! trans('menus.backend.log-viewer.logs') !!}</span>
                        </a>
                    </li>
                </ul>
            </li>

            @endauth

            <hr class="sidebar-hr"/>
            <li>
                <a href="{!! route('frontend.standings') !!}">
                    <i class="fa fa-flag-checkered"></i>
                    <span>Results</span>
                </a>
            </li>
            <hr class="sidebar-hr"/>
            <li>
                <a href="{!! route('frontend.auth.logout') !!}">
                    <i class="fa fa-sign-out"></i>
                    <span>{!! trans('navs.general.logout') !!}</span>
                </a>
            </li>

        </ul><!-- /.sidebar-menu -->

    </section><!-- /.sidebar -->
</aside>