<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>Your Personal Development Plan</h2>

		<p>Dear {{ $name }},</p>

		<p>Every golfer is different, both in how they swing and how they learn. At Brian Jacobs Golf, we're committed to ensuring your instruction is personalized to how you learn and the goals you want to accomplish. To that end, we've created a development plan for you that we'll be able to use in crafting your goals and tracking your progress toward those goals in the coming weeks and months.</p>

		<p>Within your development plan, we'll be able to create goals, have conversations about those goals, and even enter statistics for practice, TrackMan Combine results, tournament results, and rounds on the golf course. This is a great way to keep track of everything related to your instruction with us and we encourage you to take advantage of it. You can use the link below to get to your development plan and start setting goals today!</p>

		<p><a href="{{ route('plan', [$userId]) }}">Your Personal Development Plan</a></p>

		<p>Thank you, we look forward to serving you.</p>
	</body>
</html>