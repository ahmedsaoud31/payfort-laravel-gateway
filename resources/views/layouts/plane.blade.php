<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }}</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1" name="viewport"/>
	<meta content="" name="description"/>
	<meta content="" name="Reflections"/>

    <!-- Styles -->
    <link href="{{ asset('assets/stylesheets/styles.css') }}" rel="stylesheet">
	<link href="{{ asset('css/panel.css') }}" rel="stylesheet">
	<link href="{{ asset('libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">
	<link href="{{ asset('libs/loading-bars/bars.css') }}" rel="stylesheet">
    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
	<script type="text/javascript" src="{{ asset('js/jquery.js') }}"></script>
	
    @yield('body')
	<script src="{{ asset('assets/scripts/frontend.js') }}" type="text/javascript"></script>
	<script type="text/javascript" src="{{ asset('libs/sweetalert2/sweetalert2.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('libs/loading-bars/bars.js') }}"></script>
	@yield('script')
</body>
</html>
