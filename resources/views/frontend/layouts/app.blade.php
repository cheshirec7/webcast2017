<!doctype html>
<html class="no-js" lang="{!! app()->getLocale() !!}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') :: The Tevis Cup</title>
    <meta name="description" content="2017 Tevis Webcast">
    <meta name="author" content="Eric Totten">
    @yield('meta')
    @yield('before-styles')

    {!! Html::style(mix('css/frontend.css')) !!}
    @yield('after-styles')
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body class="skin-black sidebar-mini">
<div class="wrapper">
    @include('frontend.includes.header')
    @include('frontend.includes.sidebar')
    <div class="content-wrapper">
        <section class="content-header">
            @yield('page-header')
        </section>
        <section class="content">
            <div class="loader" style="display: none;">
                <div class="ajax-spinner ajax-skeleton"></div>
            </div>
            {{--            @include('includes.partials.messages')--}}
            @yield('content')
        </section>
    </div>
    @include('frontend.includes.footer')
</div>
@yield('before-scripts')
{!! Html::script(mix('js/frontend.js')) !!}
{!! Html::script('js/jquery.countdown.min.js') !!}
<script>
    $(function () {

        let now = new Date(),
            eventStart = new Date(2017, 7, 5, 5, 15, 0),
            eventEnd =   new Date(2017, 7, 6, 5, 15, 0);

        if (now > eventEnd) {
            $("#scount-line1").text('The Event Has Ended');
            $("#scount-line2").text('');
            $("#hcount-line1").text('The Event');
            $("#hcount-line2").text('Has Ended');
        } else {
            if (now < eventStart) {
                $("#scount-line1").text('The Event Starts In');
                $("#scount-line2")
                    .countdown(eventEnd, function(event) {
                        $(this).text( event.strftime('%Dd %Hh %Mm %Ss') );
                    });
                $("#hcount-line1").text('The Event Starts In');
                $("#hcount-line2")
                    .countdown(eventEnd, function(event) {
                        $(this).text( event.strftime('%Dd %Hh %Mm %Ss') );
                    });
            } else {
                $("#scount-line1").text('Event Time Remaining');
                $("#scount-line2")
                    .countdown(eventEnd, function(event) {
                        $(this).text( event.strftime('%Hh %Mm %Ss') );
                    });
                $("#hcount-line1").text('Event Time Remaining');
                $("#hcount-line2")
                    .countdown(eventEnd, function(event) {
                        $(this).text( event.strftime('%Hh %Mm %Ss') );
                    });
            }
        }
    });

    (function (b, o, i, l, e, r) {
        b.GoogleAnalyticsObject = l;
        b[l] || (b[l] =
            function () {
                (b[l].q = b[l].q || []).push(arguments)
            });
        b[l].l = +new Date;
        e = o.createElement(i);
        r = o.getElementsByTagName(i)[0];
        e.src = '//www.google-analytics.com/analytics.js';
        r.parentNode.insertBefore(e, r)
    }(window, document, 'script', 'ga'));
    ga('create', 'UA-43458798-1', 'auto');
    ga('send', 'pageview');
</script>

@yield('after-scripts')
</body>
</html>