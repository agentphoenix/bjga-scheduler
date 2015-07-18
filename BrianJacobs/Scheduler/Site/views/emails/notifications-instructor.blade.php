<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<p>Below is a summary of the activity that took place with your students' development plans on {{ $date }}. You can use the links in each student's section to view the full activity on their plan.</p>

		@foreach ($notifications as $notify)
			<h3>{{ $notify['name'] }}</h3>

			<p>{{ link_to_route('plan', $notify['firstName']."'s Development Plan", [$notify['userId']]) }}</p>

			<dl>

			@if ($notify['goalCreate'] > 0 or $notify['goalUpdate'] > 0 or $notify['goalComplete'] > 0 or $notify['goalReopen'] > 0)
				<dt>Goals</dt>

				@if ($notify['goalCreate'] > 0)
					<dd>{{ $notify['goalCreate'] }} {{ Str::plural('goal', $notify['goalCreate']) }} added</dd>
				@endif
				@if ($notify['goalUpdate'] > 0)
					<dd>{{ $notify['goalUpdate'] }} {{ Str::plural('goal', $notify['goalUpdate']) }} updated</dd>
				@endif
				@if ($notify['goalComplete'] > 0)
					<dd>{{ $notify['goalComplete'] }} {{ Str::plural('goal', $notify['goalComplete']) }} completed</dd>
				@endif
				@if ($notify['goalReopen'] > 0)
					<dd>{{ $notify['goalReopen'] }} {{ Str::plural('goal', $notify['goalReopen']) }} re-opened</dd>
				@endif
			@endif

			@if ($notify['statCreate'] > 0 or $notify['statUpdate'] > 0)
				<dt>Stats</dt>

				@if ($notify['statCreate'] > 0)
					<dd>{{ $notify['statCreate'] }} {{ Str::plural('stat', $notify['statCreate']) }} added</dd>
				@endif
				@if ($notify['statUpdate'] > 0)
					<dd>{{ $notify['statUpdate'] }} {{ Str::plural('stat', $notify['statUpdate']) }} updated</dd>
				@endif
			@endif

			@if ($notify['commentCreate'] > 0 or $notify['commentUpdate'] > 0)
				<dt>Comments</dt>

				@if ($notify['commentCreate'] > 0)
					<dd>{{ $notify['commentCreate'] }} {{ Str::plural('comment', $notify['commentCreate']) }} added</dd>
				@endif
				@if ($notify['commentUpdate'] > 0)
					<dd>{{ $notify['commentUpdate'] }} {{ Str::plural('comment', $notify['commentUpdate']) }} updated</dd>
				@endif
			@endif

			</dl>
		@endforeach
	</body>
</html>