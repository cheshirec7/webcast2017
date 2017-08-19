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
    <style>
        body {
            background-color: #222d32;
        }

        .main-header > .navbar {
            margin-left: 0;
        }

        .alert {
            max-width: 800px;
            margin: 20px auto;
        }
    </style>
    @yield('after-styles')
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body class="skin-black sidebar-mini">
<div class="wrapper">
    @include('frontend.includes.header-auth')
    <section class="content">
        @include('includes.partials.messages')
        @yield('content')
    </section>
</div>
@yield('before-scripts')
{!! Html::script(mix('js/frontend.js')) !!}
@yield('after-scripts')
</body>
</html>