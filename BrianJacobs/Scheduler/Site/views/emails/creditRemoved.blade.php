<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>{{ $subject }}</h2>

		@if ($email == 'user')
			<p>Your {{ $type }} credit of {{ $value }} with Brian Jacobs Golf has been removed from your account. If you believe this was done in error, please contact Brian Jacobs Golf to resolve the issue.</p>
		@endif

		@if ($email == 'email')
			<p>The {{ $type }} credit of {{ $value }} with Brian Jacobs Golf that you were given has been removed. If you believe this was done in error or have further questions, please contact Brian Jacobs Golf.</p>
		@endif
	</body>
</html>