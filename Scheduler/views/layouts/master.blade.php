<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Brian Jacobs Golf &bull; @yield('title')</title>
	<meta name="description" content="Brian Jacobs Golf is committed to providing the best golf instruction in western New York and helping golfers of all skills be more consistent and enjoy the game more.">
	<meta name="author" content="Brian Jacobs">
	<meta name="viewport" content="width=device-width">
	<link rel="icon" type="image/x-icon" href="{{ URL::asset('favicon.ico?v2') }}">
	<link rel="apple-touch-icon-precomposed" href="{{ URL::asset('apple-touch-icon.png') }}">
	
	<!--[if lt IE 9]>
	{{ HTML::script('js/html5shiv.js') }}
	<![endif]-->

	@if (App::environment() == 'production')
		<link href="http://fonts.googleapis.com/css?family=Exo+2:400,500,500italic,600" rel="stylesheet">
		<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,600" rel="stylesheet">
		<link href="http://fonts.googleapis.com/css?family=Crete+Round" rel="stylesheet">
		<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
	@else
		<link href="http://localhost/global/bootstrap/3.2/css/bootstrap.min.css" rel="stylesheet">
	@endif

	{{ HTML::style('css/bootstrap-datetimepicker.min.css') }}
	{{ HTML::style('css/style.css') }}
	{{ HTML::style('css/fonts.css') }}
	{{ HTML::style('css/responsive.css') }}
	@yield('styles')

	<!--[if lt IE 9]>
	{{ HTML::style('css/ie.css') }}
	<![endif]-->

	<!-- High pixel density displays -->
	<link rel='stylesheet' href='{{ URL::asset('css/retina.css') }}' media='only screen and (-moz-min-device-pixel-ratio: 2), only screen and (-o-min-device-pixel-ratio: 2/1), only screen and (-webkit-min-device-pixel-ratio: 2), only screen and (min-device-pixel-ratio: 2)'>
</head>
@if (App::environment() == 'foo')
	{{ HTML::style('css/local.css') }}

	<body class="dev">
@else
	<body>
@endif
	
	@include('partials.nav-main')

	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-2">
				@include('partials.nav-sub')
			</div>
			<div class="col-xs-12 col-sm-12 col-md-9 col-lg-10 content slideRight">
				<section>
					@if (Session::has('message'))
						<div class="alert alert-{{ Session::get('messageStatus') }}">{{ Session::get('message') }}</div>
					@endif
					
					@yield('content')
				</section>
			</div>
		</div>
	</div>

	@include('partials.report-problem')
	@yield('modals')

	@if (App::environment() == 'production')
		<!--[if lt IE 9]>
			<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
		<![endif]-->
		<!--[if gte IE 9]><!-->
			<script src="http://code.jquery.com/jquery-2.1.1.min.js"></script>
		<!--<![endif]-->

		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	@else
		<!--[if lt IE 9]>
			<script src="http://localhost/global/jquery/jquery-1.11.1.min.js"></script>
		<![endif]-->
		<!--[if gte IE 9]><!-->
			<script src="http://localhost/global/jquery/jquery-2.1.1.min.js"></script>
		<!--<![endif]-->

		<script src="http://localhost/global/bootstrap/3.2/js/bootstrap.min.js"></script>
	@endif
	<!--[if lt IE 9]>
		{{ HTML::script('js/respond.min.js') }}
	<![endif]-->
	{{ HTML::script('js/trunk.js') }}
	<script>

		$('#navToggle').click(function(e)
		{
			e.preventDefault();
		});

		// Destroy all modals when they're hidden
		$('.modal').on('hidden.bs.modal', function()
		{
			$('.modal').removeData('bs.modal');
		});

		$(document).ready(function()
		{
			$('.js-tooltip-right').tooltip({
				placement: 'right',
				container: 'body',
				html: true
			});
			$('.js-tooltip-left').tooltip({
				placement: 'left',
				container: 'body',
				html: true
			});
			$('.js-tooltip-top').tooltip({
				placement: 'top',
				container: 'body',
				html: true
			});
			$('.js-tooltip-bottom').tooltip({
				placement: 'bottom',
				container: 'body',
				html: true
			});
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