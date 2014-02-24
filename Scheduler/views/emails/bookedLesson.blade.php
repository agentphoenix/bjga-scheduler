<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<p>You have successfully booked a {{ $service }} for {{ $date }} from {{ $start }} to {{ $end }}. Make sure to take note of your appointment time and try to arrive about 5 to 10 minutes before the scheduled start time so we can begin on time.</p>

		@if ($recurring)
			<p>Since this is a recurring service, you will have additional scheduled appointments. You can log in to the Brian Jacobs Golf Scheduler to see your full schedule. For reference, you will have {{ $additional }} additional appointments {{ $days }} days apart.</p>
		@endif

		<p>A reminder email will be sent the day of your appointment.</p>

		<p>Thank you, we look forward to serving you.</p>
	</body>
</html>