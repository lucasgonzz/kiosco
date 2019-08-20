<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>KioscoVerde</title>

        <!-- Scripts -->
        <script type="text/javascript" src="https://kit.fontawesome.com/6a9cf36c74.js"></script>
        
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" href="https://unpkg.com/buefy/dist/buefy.min.css">
		<!-- <link rel="stylesheet" href="{{ asset('css/toastr.css') }}"> -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link rel="stylesheet" href="{{ asset('css/toastr.css') }}">
        <link rel="stylesheet" href="{{ asset('css/buefy.css') }}">
    </head>
    <body>
    	@include('nav')
		
		<div class="container p-b-50">
    		@yield('content')
    	</div>


        <script src="https://unpkg.com/buefy/dist/buefy.min.js"></script>
        <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
        <script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        @yield('scripts')
    </body>
</html>
