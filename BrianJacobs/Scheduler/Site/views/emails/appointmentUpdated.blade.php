<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<p>A {{ $service }} that you are registered for has been updated by the Brian Jacobs Golf staff. Your updated remaining schedule is below. If you believe this change has been made in error, please contact us (contact@brianjacobsgolf.com).</p>

		<p>Thank you, we look forward to serving you.</p>

		<ul>
			@foreach ($appointments as $appt)
				<li>{{ $appt }}</li>
			@endforeach
		</ul>
	</body>
</html>