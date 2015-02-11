@if (count($items) > 0)
	<article id="cd-timeline" class="cd-container">
		@foreach ($items as $item)
			@if ($item instanceof Goal)
				<div class="cd-timeline-block unchanged cd-timeline-goal">
					<div class="cd-timeline-img">
						<span class="icn-size-32">
							@if ((bool) $item->completed)
								{{ $_icons['check'] }}
							@else
								{{ $_icons['target'] }}
							@endif
						</span>
					</div> <!-- cd-timeline-img -->

					<div class="cd-timeline-content">
						<h2>
							{{ $item->present()->title }}
							@if ((bool) $item->completed)
								<small>Completed on {{ $item->present()->completedDate }}</small>
							@endif
						</h2>
						{{ $item->present()->summary }}
						<div class="visible-xs visible-sm">
							<p><a href="{{ route('my-plan.goal', [$item->id]) }}" class="btn btn-default btn-lg btn-block">View Goal</a></p>
						</div>
						<div class="visible-md visible-lg">
							<a href="{{ route('my-plan.goal', [$item->id]) }}" class="btn btn-default btn-sm">View Goal</a>
						</div>
						<span class="cd-date">{{ $item->present()->created }}</span>
					</div> <!-- cd-timeline-content -->
				</div> <!-- cd-timeline-block -->
			@endif

			@if ($item instanceof Conversation)
				<div class="cd-timeline-block unchanged cd-timeline-conversation">
					<div class="cd-timeline-img">
						<span class="icn-size-32">{{ $_icons['comments'] }}</span>
					</div> <!-- cd-timeline-img -->

					<div class="cd-timeline-content">
						@if ( ! empty($item->goal_id))
							<h2>Comment Added to &ldquo;{{ $item->present()->goal }}&rdquo;</h2>
						@endif
						@if ( ! empty($item->plan_id))
							<h2>Comment Added to Development Plan</h2>
						@endif
						{{ $item->present()->content }}
						@if ( ! empty($item->goal_id))
							<div class="visible-xs visible-sm">
								<p><a href="{{ route('my-plan.goal', [$item->goal->id]) }}" class="btn btn-default btn-lg btn-block">View Goal</a></p>
							</div>
							<div class="visible-md visible-lg">
								<a href="{{ route('my-plan.goal', [$item->goal->id]) }}" class="btn btn-default btn-sm">View Goal</a>
							</div>
						@endif
						<span class="cd-date">{{ $item->present()->created }}</span>
					</div> <!-- cd-timeline-content -->
				</div> <!-- cd-timeline-block -->
			@endif

			@if ($item instanceof Stat)
				<div class="cd-timeline-block unchanged cd-timeline-stat">
					<div class="cd-timeline-img">
						<span class="icn-size-32">{{ $_icons['stats'] }}</span>
					</div> <!-- cd-timeline-img -->

					<div class="cd-timeline-content">
						<h2>Stats Added to &ldquo;{{ $item->present()->goal }}&rdquo;</h2>
						{{ $item->present()->summary }}
						<div class="visible-xs visible-sm">
							<p><a href="{{ route('my-plan.goal', [$item->goal->id]) }}" class="btn btn-default btn-lg btn-block">View Goal</a></p>
						</div>
						<div class="visible-md visible-lg">
							<a href="{{ route('my-plan.goal', [$item->goal->id]) }}" class="btn btn-default btn-sm">View Goal</a>
						</div>
						<span class="cd-date">{{ $item->present()->created }}</span>
					</div> <!-- cd-timeline-content -->
				</div> <!-- cd-timeline-block -->
			@endif
		@endforeach
	</article>
@endif

<div class="cd-beginning">
	<h2>Development Plan Created<small>{{ $plan->present()->created }}</small></h2>
</div>