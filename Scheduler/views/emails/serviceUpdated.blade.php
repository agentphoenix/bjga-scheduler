<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>Service Update: {{ $service->name }}</h2>

		<p>{{ $service->name }} has recently been updated. We've included updated information about the service below for your convenience. Please verify the information and ensure you've taken note of any changes to the schedule. If you have questions, please contact us (contact@brianjacobsgolf.com).</p>

		<p>Thank you, we look forward to serving you.</p>

		<h3>Details</h3>

		<p><strong>Description</strong> - {{ $service->description }}</p>
		<p><strong>Price</strong> - ${{ $service->price }}</p>

		<h3>Schedule</h3>

		<ul>
		@foreach ($schedule as $s)
			<li>{{ $s->start->format('l F jS, Y') }}, {{ $s->start->format('g:ia') }} - {{ $s->end->format('g:ia') }}</li>
		@endforeach
		</ul>
	</body>
</html>