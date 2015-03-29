<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		@if ($recurring)
			<p>You have successfully booked a {{ $service }} for {{ $date }} from {{ $start }} to {{ $end }}. Make sure to take note of your appointment time and try to arrive 10 minutes before the scheduled start time so we can begin on time.</p>

			<p>Since this is a recurring service, you will have additional scheduled appointments. The schedule of your appointments and total due for each is below. You can also log in to the Brian Jacobs Golf Scheduler to see your full schedule.</p>

			<p>A reminder email will be sent 24 hours before each appointment.</p>

			<ul>
			@foreach ($appointments as $a)
				<li>{{ $a['start'] }}<br>Location: <strong>{{ $a['location'] }}</strong><br>Total Due: <strong>{{ $a['due'] }}</strong></li>
			@endforeach
			</ul>
		@else
			<p>You have successfully booked a {{ $service }} for {{ $date }} from {{ $start }} to {{ $end }} at {{ $location }}. Make sure to take note of your appointment time and try to arrive 10 minutes before the scheduled start time so we can begin on time.</p>

			<p>A reminder email will be sent 24 hours before your appointment.</p>
		@endif

		<p>Thank you, we look forward to serving you.</p>
	</body>
</html>