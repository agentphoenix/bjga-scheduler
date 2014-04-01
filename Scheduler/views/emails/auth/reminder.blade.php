<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<p>A password reset has been requested for your account. If you did not initiate this reset, please contact Brian Jacobs Golf immediately (admin@brianjacobsgolf.com).</p>

		<p>Using the link below, you can reset your password to something you can more easily remember.</p>

		<p>{{ URL::to('password/reset', array($token)) }}</p>
	</body>
</html>