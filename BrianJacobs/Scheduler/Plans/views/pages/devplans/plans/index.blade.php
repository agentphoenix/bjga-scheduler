@extends('layouts.master')

@section('title')
	Development Plans
@stop

@section('content')
	<h1>Development Plans</h1>

	@if ($createAllowed)
		<div class="visible-xs visible-sm">
			<p><a href="#" class="btn btn-primary btn-lg btn-block js-planAction" data-action="create">Add Development Plan</a></p>
		</div>
		<div class="visible-md visible-lg">
			<div class="btn-toolbar">
				<div class="btn-group">
					<a href="#" class="btn btn-primary btn-sm icn-size-16 js-planAction" data-action="create">{{ $_icons['add'] }}</a>
				</div>
			</div>
		</div>
	@endif

	@if ($plans->count() > 0)
		<div class="row">
			<div class="col-sm-6 col-md-6 col-lg-4">
				{{ Form::text('search', null, ['placeholder' => 'Search for development plans', 'class' => 'form-control search-control', 'id' => 'searchPlans']) }}
			</div>
		</div>

		<div class="data-table data-table-bordered data-table-striped" id="plansTable">
		@foreach ($plans as $plan)
			<div class="row">
				<div class="col-md-9">
					<p class="lead">{{ $plan->user->present()->name }}</p>
					
					@if ($_currentUser->access() == 4 or $plan->instructors->count() > 1)
						<p class="text-sm text-muted">
							<strong>{{ Str::plural('Instructor', $plan->instructors->count()) }}:</strong>
							{{ $plan->present()->instructors }}
						</p>
					@endif
					
					@if ($plan->activeGoals->count() > 0)
						<p class="text-sm text-muted">{{ Str::plural('goal', $plan->activeGoals->count()) }}</p>
					@endif
				</div>
				<div class="col-md-3">
					<div class="visible-xs visible-sm">
						<p><a href="{{ route('plan', [$plan->user_id]) }}" class="btn btn-default btn-lg btn-block">View Development Plan</a></p>

						<p><a href="#" class="btn btn-default btn-lg btn-block js-planAction" data-action="instructor" data-id="{{ $plan->id }}">Manage Instructors</a></p>
						
						<p><a href="#" class="btn btn-danger btn-lg btn-block js-planAction" data-action="remove" data-id="{{ $plan->id }}">Remove Development Plan</a></p>
					</div>
					<div class="visible-md visible-lg">
						<div class="btn-toolbar pull-right">
							<div class="btn-group">
								<a href="{{ route('plan', [$plan->user_id]) }}" class="btn btn-default btn-sm icn-size-16 js-tooltip-top" data-title="View Plan">{{ $_icons['target'] }}</a>
							</div>
							<div class="btn-group">
								<a href="#" class="btn btn-default btn-sm icn-size-16 js-tooltip-top js-planAction" data-title="Manage Instructors" data-action="instructor" data-id="{{ $plan->id }}">{{ $_icons['school'] }}</a>
							</div>
							<div class="btn-group">
								<a href="#" class="btn btn-danger btn-sm icn-size-16 js-planAction js-tooltip-top" data-title="Remove Plan" data-action="remove" data-id="{{ $plan->id }}">{{ $_icons['remove'] }}</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		@endforeach
		</div>
	@else
		{{ alert('warning', "No development plans found") }}
	@endif
@stop

@section('modals')
	{{ modal(['id' => 'addPlan', 'header' => "Add Development Plan"]) }}
	{{ modal(['id' => 'removePlan', 'header' => "Remove Development Plan"]) }}
	{{ modal(['id' => 'instructor', 'header' => "Add Instructor to Development Plan"]) }}
@stop

@section('scripts')
	{{ HTML::script('js/jquery.quicksearch.min.js') }}
	<script>
		$('#searchPlans').quicksearch('#plansTable > div', {
			hide: function()
			{
				$(this).addClass('hide');
			},
			show: function()
			{
				$(this).removeClass('hide');
			}
		});

		$('.js-planAction').on('click', function(e)
		{
			e.preventDefault();

			var action = $(this).data('action');
			var id = $(this).data('id');

			if (action == 'create')
			{
				$('#addPlan').modal({
					remote: "{{ route('admin.plan.create') }}"
				}).modal('show');
			}

			if (action == 'remove')
			{
				$('#removePlan').modal({
					remote: "{{ URL::to('admin/plan') }}/" + id + "/remove"
				}).modal('show');
			}

			if (action == 'instructor')
			{
				$('#instructor').modal({
					remote: "{{ URL::to('admin/plan') }}/" + id + "/edit"
				}).modal('show');
			}
		});

		$(document).on('click', '.js-removeInstructor', function(e)
		{
			e.preventDefault();

			var button = $(this);

			$.ajax({
				url: "{{ route('admin.plan.removeInstructor') }}",
				type: "POST",
				dataType: "json",
				data: {
					"_token": "{{ csrf_token() }}",
					instructor: $(this).data('instructor'),
					plan: $(this).data('plan')
				},
				success: function (data)
				{
					button.closest('.row').fadeOut('normal', function()
					{
						$(this).remove();
					});
				}
			});
		});
	</script>
@stop