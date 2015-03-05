<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>Service Update: {{ $name }}</h2>

		<p>{{ $name }} has recently been updated. We've included updated information about the service below for your convenience. Please verify the information and ensure you've taken note of any changes to the schedule. If you have questions, please contact us (contact@brianjacobsgolf.com).</p>

		<p>Thank you, we look forward to serving you.</p>

		<h3>Details</h3>

		<p><strong>Description</strong> - {{ $description }}</p>
		<p><strong>Location</strong> - {{ $location }}</p>
		<p><strong>Price</strong> - {{ $price }}</p>

		<h3>Schedule</h3>

		<ul>
		@foreach ($schedule as $s)
			<li>{{ $s['start'] }} - {{ $s['end'] }}</li>
		@endforeach
		</ul>
	</body>
</html>