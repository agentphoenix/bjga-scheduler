<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>Your Personal Development Plan</h2>

		<p>Dear {{ $name }},</p>

		<p>It's been {{ $days }} since there was any activity on your development plan...</p>

		<p><a href="{{ route('plan') }}">Your Personal Development Plan</a></p>

		<p>Thank you, we look forward to serving you.</p>
	</body>
</html>