@if ((bool) $goal->completed)
	<div class="cd-ending">
		<h2>{{ $_icons['check'] }} Goal Accomplished!<small>{{ $goal->present()->completedDate }}</small></h2>
	</div>
@else
	@if ($goal->completion)
		{{ alert('alert-info', $goal->present()->completionCriteria) }}
	@endif
@endif

@if (count($items) > 0)
	<article id="cd-timeline" class="cd-container">
		@foreach ($items as $item)
			@if ($item instanceof Comment)
				<div class="cd-timeline-block unchanged cd-timeline-comment">
					<div class="cd-timeline-img">
						<span class="icn-size-32">{{ $_icons['comments'] }}</span>
					</div> <!-- cd-timeline-img -->

					<div class="cd-timeline-content">
						{{ $item->present()->content }}

						@if ( ! (bool) $goal->completed)
							<div class="visible-xs visible-sm">
								<a href="#" class="btn btn-default btn-lg btn-block js-goalAction" data-action="comment-add" data-item="{{ $goal->id }}">Reply</a><br><br>

								@if ($_currentUser->isStaff() or ! $_currentUser->isStaff() and $_currentUser->id == $item->user_id)
									<a href="#" class="btn btn-default btn-lg btn-block js-goalAction" data-action="comment-edit" data-item="{{ $item->id }}">Edit Comment</a>

									<a href="#" class="btn btn-danger btn-lg btn-block js-goalAction" data-action="comment-remove" data-item="{{ $item->id }}">Remove Comment</a>
								@endif
							</div>
							<div class="visible-md visible-lg">
								<a href="#" class="btn btn-default btn-sm js-goalAction" data-action="comment-add" data-item="{{ $goal->id }}">Reply</a>

								@if ($_currentUser->isStaff() or ! $_currentUser->isStaff() and $_currentUser->id == $item->user_id)
									<a href="#" class="btn btn-link js-goalAction" data-action="comment-edit" data-item="{{ $item->id }}">{{ $_icons['edit'] }}</a>

									<a href="#" class="btn btn-link js-goalAction" data-action="comment-remove" data-item="{{ $item->id }}">{{ $_icons['remove'] }}</a>
								@endif
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
								<a href="#" class="btn btn-default btn-lg btn-block js-goalAction" data-action="comment-add" data-item="{{ $goal->id }}">Reply</a>

								@if ($item->type != 'message')
									<br><br>

									<a href="#" class="btn btn-default btn-lg btn-block js-goalAction" data-action="stats-edit" data-item="{{ $item->id }}">Edit Stat</a>

									<a href="#" class="btn btn-danger btn-lg btn-block js-goalAction" data-action="stats-remove" data-item="{{ $item->id }}">Remove Stat</a>
								@endif
							</div>
							<div class="visible-md visible-lg">
								<a href="#" class="btn btn-default btn-sm js-goalAction" data-action="comment-add" data-item="{{ $goal->id }}">Reply</a>

								@if ($item->type != 'message')
									<a href="#" class="btn btn-link js-goalAction" data-action="stats-edit" data-item="{{ $item->id }}">{{ $_icons['edit'] }}</a>

									<a href="#" class="btn btn-link js-goalAction" data-action="stats-remove" data-item="{{ $item->id }}">{{ $_icons['remove'] }}</a>
								@endif
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
						@if ( ! (bool) $goal->completed)
							<div class="visible-xs visible-sm">
								<a href="#" class="btn btn-default btn-lg btn-block js-goalAction" data-action="comment-add" data-item="{{ $goal->id }}">Reply</a><br><br>

								@if ($goal->plan->activeGoals->count() > 1)
									<a href="#" class="btn btn-default btn-lg btn-block js-goalAssociation" data-item="{{ $item->id }}">Edit Lesson</a>
								@endif

								<a href="#" class="btn btn-danger btn-lg btn-block js-goalAction" data-action="lesson-remove" data-item="{{ $item->id }}">Remove Lesson</a>
							</div>
							<div class="visible-md visible-lg">
								<a href="#" class="btn btn-default btn-sm js-goalAction" data-action="comment-add" data-item="{{ $goal->id }}">Reply</a>

								@if ($goal->plan->activeGoals->count() > 1)
									<a href="#" class="btn btn-link js-goalAssociation" data-item="{{ $item->id }}">{{ $_icons['edit'] }}</a>
								@endif

								<a href="#" class="btn btn-link js-goalAction" data-action="lesson-remove" data-item="{{ $item->id }}">{{ $_icons['remove'] }}</a>
							</div>
						@endif
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