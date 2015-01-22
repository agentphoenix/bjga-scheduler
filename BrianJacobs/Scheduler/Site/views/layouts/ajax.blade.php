<html>
	<head></head>
	<body>
		@yield('content')

		@if (App::environment() == 'production')
			<!--[if lt IE 9]>
				<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
			<![endif]-->
			<!--[if gte IE 9]><!-->
				<script src="http://code.jquery.com/jquery-2.1.0.min.js"></script>
			<!--<![endif]-->

			<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
		@else
			<script src="//localhost/global/jquery/jquery-2.1.0.min.js"></script>
			<script src="//localhost/global/bootstrap/3.2/js/bootstrap.min.js"></script>
		@endif
		
		@yield('scripts')
	</body>
</html>