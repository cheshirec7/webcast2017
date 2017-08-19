<!doctype html>
<html class="no-js" lang="{!! app()->getLocale() !!}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', app_name())</title>
    <meta name="description" content="2017 Tevis Webcast">
    <meta name="author" content="Eric Totten">
    @yield('meta')
    @yield('before-styles')
    {!! Html::style(mix('css/backend.css')) !!}
    @yield('after-styles')
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body class="skin-{{ config('backend.theme') }} {!! config('backend.layout') !!}">
@include('includes.partials.logged-in-as')
<div class="wrapper">
    @include('backend.includes.header')
    @include('backend.includes.sidebar')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            @yield('page-header')

            {{-- Change to Breadcrumbs::render() if you want it to error to remind you to create the breadcrumbs for the given route --}}
            {!! Breadcrumbs::renderIfExists() !!}
        </section>

        <section class="content">
            <div class="loader" style="display: none;">
                <div class="ajax-spinner ajax-skeleton"></div>
            </div><!--loader-->

            @include('includes.partials.messages')
            @yield('content')
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

    @include('backend.includes.footer')
</div><!-- ./wrapper -->
@yield('before-scripts')
{!! Html::script(mix('js/backend.js')) !!}
@yield('after-scripts')
</body>
</html>