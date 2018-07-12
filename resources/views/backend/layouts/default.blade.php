<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link href="{{ asset('favicon.ico') }}" rel="icon">
        <title>{{!empty($title) ? $title.' | '.SITE_TITLE : SITE_TITLE }}</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link href="{{ asset("/backend/bootstrap/css/bootstrap.min.css") }}" rel="stylesheet" type="text/css" />
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="http://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ asset("/backend/dist/css/admin.min.css")}}" rel="stylesheet" type="text/css" />
        @yield('requirecss')
        @yield('inlinecss')
        <script>
            window.Laravel = <?php echo json_encode(['csrfToken' => csrf_token(),]); ?>;

            var base_url                = "{{ url('/') }}";
            var asset_url               = "{{ asset('/') }}";

        </script>
    </head>
    <body class="hold-transition login-page">
        <div id="app">
            <div class="container">
                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
            @yield('content')
        </div>
        <script src="{{ asset ("/backend/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>
        <script src="{{ asset ("/backend/bootstrap/js/bootstrap.min.js") }}" type="text/javascript"></script>
        <script src="{{ asset ("/backend/dist/js/app.js") }}" type="text/javascript"></script>
        @yield('requirejs')
        @yield('inlinejs')
    </body>
</html>
