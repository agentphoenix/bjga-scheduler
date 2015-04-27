@if ((bool) $goal->completed)
	<div class="cd-ending">
		<h2>{{ $_icons['check'] }} Goal Accomplished!<small>{{ $goal->present()->completedDate }}</small></h2>
	</div>
@endif

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
								<small>Completed on {{ $item->present()->completed }}</small>
							@endif
						</h2>
						{{ $item->present()->summary }}
						<a href="{{ route('plan.goal', [$userId, $item->id]) }}" class="btn btn-default btn-sm">More Info</a>
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
						{{ $item->present()->content }}
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
						{{ $item->present()->summary }}
						<span class="cd-date">{{ $item->present()->created }}</span>
					</div> <!-- cd-timeline-content -->
				</div> <!-- cd-timeline-block -->
			@endif
		@endforeach
	</article>
@endif

<div class="cd-beginning">
	<h2>Goal Created<small>{{ $goal->present()->created }}</small></h2>
</div>