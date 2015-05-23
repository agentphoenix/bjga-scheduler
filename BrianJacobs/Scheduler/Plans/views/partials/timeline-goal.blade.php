@if ((bool) $goal->completed)
	<div class="cd-ending">
		<h2>{{ $_icons['check'] }} Goal Accomplished!<small>{{ $goal->present()->completedDate }}</small></h2>
	</div>
@endif

@if (count($items) > 0)
	<article id="cd-timeline" class="cd-container">
		@foreach ($items as $item)
			@if ($item instanceof Conversation)
				<div class="cd-timeline-block unchanged cd-timeline-conversation">
					<div class="cd-timeline-img">
						<span class="icn-size-32">{{ $_icons['comments'] }}</span>
					</div> <!-- cd-timeline-img -->

					<div class="cd-timeline-content">
						{{ $item->present()->content }}

						@if ( ! (bool) $goal->completed)
							<div class="visible-xs visible-sm">
								<a href="#" class="btn btn-default btn-lg btn-block js-goalAction" data-action="conversation-add" data-item="{{ $goal->id }}">Reply</a><br><br>

								<a href="#" class="btn btn-danger btn-lg btn-block js-goalAction" data-action="conversation-remove" data-item="{{ $item->id }}">Remove Comment</a>
							</div>
							<div class="visible-md visible-lg">
								<a href="#" class="btn btn-default btn-sm js-goalAction" data-action="conversation-add" data-item="{{ $goal->id }}">Reply</a>

								<a href="#" class="btn btn-link js-goalAction" data-action="conversation-remove" data-item="{{ $item->id }}">{{ $_icons['remove'] }}</a>
							</div>
						@endif
						<span class="cd-date">{{ $item->present()->created }}</span>
					</div> <!-- cd-timeline-content -->
				</div> <!-- cd-timeline-block -->
			@endif

			@if ($item instanceof Stat)
				<div class="cd-timeline-block unchanged cd-timeline-stat">
					<div class="cd-timeline-img">
						<span class="icn-size-32">
							@if ($item->type != 'message' and $item->type != 'tournament')
								{{ $_icons['stats'] }}
							@else
								@if ($item->type == 'tournament')
									{{ $_icons['podium'] }}
								@else
									{{ $_icons[$item->icon] }}
								@endif
							@endif
						</span>
					</div> <!-- cd-timeline-img -->

					<div class="cd-timeline-content">
						<h2>{{ $item->present()->header }}</h2>

						{{ $item->present()->summary }}

						@if ( ! (bool) $goal->completed)
							<div class="visible-xs visible-sm">
								<a href="#" class="btn btn-default btn-lg btn-block js-goalAction" data-action="conversation-add" data-item="{{ $goal->id }}">Reply</a>
							</div>
							<div class="visible-md visible-lg">
								<a href="#" class="btn btn-default btn-sm js-goalAction" data-action="conversation-add" data-item="{{ $goal->id }}">Reply</a>
							</div>
						@endif
						<span class="cd-date">{{ $item->present()->created }}</span>
					</div> <!-- cd-timeline-content -->
				</div> <!-- cd-timeline-block -->
			@endif

			@if ($item instanceof StaffAppointmentModel)
				<div class="cd-timeline-block unchanged cd-timeline-stat">
					<div class="cd-timeline-img">
						<span class="icn-size-32">{{ $_icons['golf'] }}</span>
					</div> <!-- cd-timeline-img -->

					<div class="cd-timeline-content">
						@if ($item->service->isRecurring())
							<h2>{{ $item->service->present()->name }} Lesson Added to &ldquo;{{ $item->goal->present()->title }}&rdquo;</h2>
						@else
							<h2>{{ $item->service->present()->name }} Added to &ldquo;{{ $item->goal->present()->title }}&rdquo;</h2>
						@endif

						<p>
							{{ $item->present()->appointmentDate }}, {{ $item->present()->appointmentTime }}<br>
							<span class="text-sm text-muted">{{ $item->present()->location }}</span>
						</p>
						<span class="cd-date">{{ $item->present()->appointmentDateForPlan }}</span>
					</div> <!-- cd-timeline-content -->
				</div> <!-- cd-timeline-block -->
			@endif
		@endforeach
	</article>
@endif

<div class="cd-beginning">
	<h2>Goal Created<small>{{ $goal->present()->created }}</small></h2>
</div>