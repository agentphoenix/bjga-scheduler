<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<p>You have successfully enrolled in the {{ $service }} program at {{ $location }}. The schedule for the program is included below. Please try to arrive about 10 minutes before the scheduled start time(s) so we can begin on time. A reminder email will be sent 24 hours before the start of the program.</p>

		<p>Thank you, we look forward to serving you.</p>

		<ul>
		@foreach ($schedule as $s)
			<li>{{ $s['start'] }} - {{ $s['end'] }}</li>
		@endforeach
		</ul>
	</body>
</html>