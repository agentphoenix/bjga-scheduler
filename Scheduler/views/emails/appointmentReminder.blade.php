<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<p>Don't forget that the {{ $service }} you booked is today from {{ $start }} to {{ $end }}!</p>

		<p>Make sure that you arrive at least 10 minutes before the start time so we can get everything started right on time. If you need to cancel this appointment, you can <a href="{{ URL::route('home') }}">log in</a> to the scheduler and cancel from there.</p>

		<p>Thank you, we look forward to serving you.</p>
	</body>
</html>