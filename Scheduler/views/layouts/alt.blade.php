<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Brian Jacobs Golf &bull; @yield('title')</title>
	<meta name="description" content="Brian Jacobs Golf Academy is committed to providing the best golf instruction in western New York and helping golfers of all skills be more consistent and enjoy the game more.">
	<meta name="author" content="Brian Jacobs">
	<meta name="viewport" content="width=device-width">
	<link rel="icon" href="{{ URL::asset('favicon.ico') }}" type="image/x-icon">
	<link rel="apple-touch-icon-precomposed" href="{{ URL::asset('apple-touch-icon.png') }}">
	
	<!--[if lt IE 9]>
	<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	@if (App::environment() == 'production')
		<link href="http://fonts.googleapis.com/css?family=Titillium+Web:400,300,600,700" rel="stylesheet">
		<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,700,600" rel="stylesheet">
		<link href="http://fonts.googleapis.com/css?family=Crete+Round" rel="stylesheet">
		<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet">
	@else
		<link href="http://localhost/global/bootstrap/3.1/css/bootstrap.min.css" rel="stylesheet">
	@endif

	{{ HTML::style('css/bootstrap-datetimepicker.min.css') }}
	{{ HTML::style('css/fonts.css') }}
	{{ HTML::style('css/style.css') }}
	{{ HTML::style('css/responsive.css') }}
	@yield('styles')

	<!--[if lt IE 9]>
	{{ HTML::style('css/ie.css') }}
	<![endif]-->

	<!-- High pixel density displays -->
	<link rel='stylesheet' href='{{ URL::asset('css/retina.css') }}' media='only screen and (-moz-min-device-pixel-ratio: 2), only screen and (-o-min-device-pixel-ratio: 2/1), only screen and (-webkit-min-device-pixel-ratio: 2), only screen and (min-device-pixel-ratio: 2)'>
</head>
@if (App::environment() == 'foo')
	<body class="dev">
		<div class="progress progress-striped">
			<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
				<div class="bar">This page is for testing only!</div>
			</div>
		</div>
@else
	<body>
@endif

	<div class="container wrapper">
		@include('partials.nav-main')

		@include('partials.header')
		
		@include('partials.nav-sub')
		
		<section>
			<div class="inner">
				@if (Session::has('message'))
					<br>
					<div class="alert alert-{{ Session::get('messageStatus') }}">{{ Session::get('message') }}</div>
				@endif
				
				@yield('content')
			</div>
		</section>
		
		@include('partials.footer')
	</div>

	<div class="copyright">
		<div class="container">
			&copy; {{ Date::now()->year }} Brian Jacobs Golf
		</div>
	</div>

	@if (App::environment() == 'production')
		<!--[if lt IE 9]>
			<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
		<![endif]-->
		<!--[if gte IE 9]><!-->
			<script src="http://code.jquery.com/jquery-2.1.0.min.js"></script>
		<!--<![endif]-->

		<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
	@else
		<script src="http://localhost/global/jquery/jquery-2.1.0.min.js"></script>
		<script src="http://localhost/global/bootstrap/3.1/js/bootstrap.min.js"></script>
	@endif
	<script>

		// Destroy all modals when they're hidden
		$('.modal').on('hidden.bs.modal', function()
		{
			$('.modal').removeData('bs.modal');
		});

	</script>
	@yield('scripts')
	
	@if (App::environment() == 'production')
		<script>
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', 'UA-36788318-1']);
			_gaq.push(['_trackPageview']);

			(function() {
				var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			})();
		</script>
	@endif
</body>
</html>