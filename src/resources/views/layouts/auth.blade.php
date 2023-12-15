<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link rel="icon" href="/assets/images/logo.webp" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link
        rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&amp;display=fallback"
    >
    <link
        rel="stylesheet"
        href="{{asset('/assets/vendor/fontawesome/css/all.css')}}"
    >
    <link
        rel="stylesheet"
        href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"
    >
    <link
        rel="stylesheet"
        href="{{asset('/assets/vendor/adminlte/icheck-bootstrap.min.css')}}"
    >
    <link
        rel="stylesheet"
        href="{{asset('/assets/vendor/adminlte/adminlte.min.css')}}"
    >
    @stack('page-css')
</head>
<body class="hold-transition login-page">

    @yield('main')
    
    <script src="{{asset('/assets/vendor/adminlte/jquery.min.js')}}"></script>
    <script src="{{asset('/assets/vendor/adminlte/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('/assets/vendor/adminlte/adminlte.min.js')}}"></script>
    @stack('page-js')
</body>
</html>