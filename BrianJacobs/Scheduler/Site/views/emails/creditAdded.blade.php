<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>{{ $subject }}</h2>

		@if ($email == 'user')
			<p>Credit has just been added to your account! You've been given <strong>{{ $value }}</strong> of credit to use on any lesson service offered by Brian Jacobs Golf. Since this has been applied directly to your account, the next time you book a lesson, this credit will automatically be used.</p>

			@if ($type == 'time')
				<p><strong>Important:</strong> This credit will expire one year from today, so make sure you book your lesson(s) soon!</p>
			@endif
		@endif

		@if ($email == 'email')
			<p>You've just been given <strong>{{ $value }}</strong> of credit with Brian Jacobs Golf to use on any lesson service we offer!</p>

			<p>In order to use this credit, you'll need to take a few moments and register for our scheduling system. Once you've finished the registration process, the credit will automatically be applied to your account.</p>

			@if ($type == 'time')
				<p><strong>Important:</strong> This credit will expire one year from when you register for the scheduling system, so make sure you book your lesson(s) soon!</p>
			@endif

			<p><a href="{{ route('home') }}">Register Now!</a></p>
		@endif
	</body>
</html>