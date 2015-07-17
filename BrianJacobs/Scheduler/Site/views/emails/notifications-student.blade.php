<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		@if ( ! empty($name))
			<p>{{ $name }},</p>
		@endif

		<p>Below is a summary of the activity that took place with your development plan on {{ $date }}. You can {{ link_to_route('plan', 'log in', [$userId]) }} to view all the details of the recent activity.</p>

		<dl>

		@if ($notifications['goalCreate'] > 0 or $notifications['goalUpdate'] > 0 or $notifications['goalComplete'] > 0 or $notifications['goalReopen'] > 0)
			<dt>Goals</dt>

			@if ($notifications['goalCreate'] > 0)
				<dd>{{ $notifications['goalCreate'] }} {{ Str::plural('goal', $notifications['goalCreate']) }} added</dd>
			@endif
			@if ($notifications['goalUpdate'] > 0)
				<dd>{{ $notifications['goalUpdate'] }} {{ Str::plural('goal', $notifications['goalUpdate']) }} updated</dd>
			@endif
			@if ($notifications['goalComplete'] > 0)
				<dd>{{ $notifications['goalComplete'] }} {{ Str::plural('goal', $notifications['goalComplete']) }} completed</dd>
			@endif
			@if ($notifications['goalReopen'] > 0)
				<dd>{{ $notifications['goalReopen'] }} {{ Str::plural('goal', $notifications['goalReopen']) }} re-opened</dd>
			@endif
		@endif

		@if ($notifications['statCreate'] > 0 or $notifications['statUpdate'] > 0)
			<dt>Stats</dt>

			@if ($notifications['statCreate'] > 0)
				<dd>{{ $notifications['statCreate'] }} {{ Str::plural('stat', $notifications['statCreate']) }} added</dd>
			@endif
			@if ($notifications['statUpdate'] > 0)
				<dd>{{ $notifications['statUpdate'] }} {{ Str::plural('stat', $notifications['statUpdate']) }} updated</dd>
			@endif
		@endif

		@if ($notifications['commentCreate'] > 0 or $notifications['commentUpdate'] > 0)
			<dt>Comments</dt>

			@if ($notifications['commentCreate'] > 0)
				<dd>{{ $notifications['commentCreate'] }} {{ Str::plural('comment', $notifications['commentCreate']) }} added</dd>
			@endif
			@if ($notifications['commentUpdate'] > 0)
				<dd>{{ $notifications['commentUpdate'] }} {{ Str::plural('comment', $notifications['commentUpdate']) }} updated</dd>
			@endif
		@endif

		</dl>
	</body>
</html>