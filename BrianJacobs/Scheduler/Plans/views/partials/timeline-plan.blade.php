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

						<p class="text-muted">
						@if ($item->conversations->count() > 0)
							<span class="icn-size-16">{{ $_icons['comments'] }}</span>
							&nbsp;<em>{{ $item->conversations->count() }}</em>
							&nbsp;&nbsp;&nbsp;
						@endif

						@if ($item->lessons->count() > 0)
							<span class="icn-size-16">{{ $_icons['golf'] }}</span>
							&nbsp;<em>{{ $item->lessons->count() }}</em>
							&nbsp;&nbsp;&nbsp;
						@endif

						@if ($item->countStats() > 0)
							<span class="icn-size-16">{{ $_icons['stats'] }}</span>
							&nbsp;<em>{{ $item->countStats() }}</em>
							&nbsp;&nbsp;&nbsp;
						@endif

						@if ($item->countTournaments() > 0)
							<span class="icn-size-16">{{ $_icons['podium'] }}</span>
							&nbsp;<em>{{ $item->countTournaments() }}</em>
							&nbsp;&nbsp;&nbsp;
						@endif
						</p>

						<div class="visible-xs visible-sm">
							<a href="{{ route('goal.show', [$userId, $item->id]) }}" class="btn btn-default btn-lg btn-block">View Goal</a><br><br>

							<a href="#" class="btn btn-default btn-lg btn-block js-planAction" data-action="goal-edit" data-item="{{ $item->id }}">Edit Goal</a>

							<a href="#" class="btn btn-danger btn-lg btn-block js-planAction" data-action="goal-remove" data-item="{{ $item->id }}">Remove Goal</a>

							@if ($item->isComplete())
								<a href="#" class="btn btn-default btn-lg btn-block js-planAction" data-action="goal-status" data-status="open" data-item="{{ $item->id }}">Re-Open Goal</a>
							@else
								<a href="#" class="btn btn-primary btn-lg btn-block js-planAction" data-action="goal-status" data-status="complete" data-item="{{ $item->id }}">Mark Complete</a>
							@endif
						</div>
						<div class="visible-md visible-lg">
							<a href="{{ route('goal.show', [$userId, $item->id]) }}" class="btn btn-default btn-sm">View Goal</a>

							<a href="#" class="btn btn-link js-planAction" data-action="goal-edit" data-item="{{ $item->id }}">{{ $_icons['edit'] }}</a>
							
							<a href="#" class="btn btn-link js-planAction" data-action="goal-remove" data-item="{{ $item->id }}">{{ $_icons['remove'] }}</a>

							@if ($item->isComplete())
								<a href="#" class="btn btn-link js-planAction js-tooltip-top" title="Re-Open Goal" data-action="goal-status" data-status="open" data-item="{{ $item->id }}">{{ $_icons['target'] }}</a>
							@else
								<a href="#" class="btn btn-link js-planAction js-tooltip-top" title="Mark Goal Complete" data-action="goal-status" data-status="complete" data-item="{{ $item->id }}">{{ $_icons['check'] }}</a>
							@endif
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
						{{ $item->present()->content }}
						@if ( ! empty($item->goal_id))
							<div class="visible-xs visible-sm">
								<p><a href="{{ route('goal.show', [$userId, $item->goal->id]) }}" class="btn btn-default btn-lg btn-block">View Goal</a></p>
							</div>
							<div class="visible-md visible-lg">
								<a href="{{ route('goal.show', [$userId, $item->goal->id]) }}" class="btn btn-default btn-sm">View Goal</a>
							</div>
						@endif
						<span class="cd-date">{{ $item->present()->created }}</span>
					</div> <!-- cd-timeline-content -->
				</div> <!-- cd-timeline-block -->
			@endif

			@if ($item instanceof Stat and $item->type != 'message')
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
						<h2>{{ $item->present()->header }} Added to &ldquo;{{ $item->present()->goal }}&rdquo;</h2>
						{{ $item->present()->summary }}
						<div class="visible-xs visible-sm">
							<p><a href="{{ route('goal.show', [$userId, $item->goal->id]) }}" class="btn btn-default btn-lg btn-block">View Goal</a></p>
						</div>
						<div class="visible-md visible-lg">
							<a href="{{ route('goal.show', [$userId, $item->goal->id]) }}" class="btn btn-default btn-sm">View Goal</a>
						</div>
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
						
						<div class="visible-xs visible-sm">
							<p><a href="{{ route('goal.show', [$userId, $item->goal->id]) }}" class="btn btn-default btn-lg btn-block">View Goal</a></p>
						</div>
						<div class="visible-md visible-lg">
							<a href="{{ route('goal.show', [$userId, $item->goal->id]) }}" class="btn btn-default btn-sm">View Goal</a>
						</div>
						<span class="cd-date">{{ $item->present()->appointmentDateForPlan }}</span>
					</div> <!-- cd-timeline-content -->
				</div> <!-- cd-timeline-block -->
			@endif
		@endforeach
	</article>
@endif

<div class="cd-beginning">
	<h2>Development Plan Created<small>{{ $plan->present()->created }}</small></h2>
</div>