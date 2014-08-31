<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>Welcome to Brian Jacobs Golf!</h2>

		<p>Dear {{ $name }},</p>

		<p>You've successfully registered for the Brian Jacobs Golf Scheduler. Using your account, you can book private lessons or sign up to attend programs put on by Brian Jacobs Golf. Below you will find the information you registered with. If you have questions, please contact us (contact@brianjacobsgolf.com).</p>

		<p>Website Address: {{ $site }}<br>
		Email Address: {{ $email }}<br>
		Password: {{ $password }}</p>

		<p>Thank you, we look forward to serving you.</p>
	</body>
</html>