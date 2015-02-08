@extends('layouts.master')

@section('title')
	My Development Plan
@stop

@section('content')
	<h1>My Development Plan</h1>

	<div class="visible-xs visible-sm">
		<div class="row">
			<div class="col-xs-6 col-sm-3">
				<p><a href="{{ route('admin.credits.create') }}" class="btn btn-block btn-lg btn-primary icn-size-16">Add Goal</a></p>
			</div>
		</div>
	</div>
	<div class="visible-md visible-lg">
		<div class="btn-toolbar">
			<div class="btn-group">
				<a href="{{ route('admin.credits.create') }}" class="btn btn-sm btn-primary icn-size-16">{{ $_icons['add'] }}</a>
			</div>
		</div>
	</div>

	@if ($plan->goals->count() > 0)
		<div class="row">
		@foreach ($plan->goals as $goal)
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">
							<div class="pull-right text-muted">
								@if ($goal->conversations->count() > 0)
									{{ $_icons['comments'] }}
								@endif
								{{ $_icons['stats'] }}
							</div>
							{{ $goal->present()->title }}
						</h3>
					</div>
					<div class="panel-body">
						{{ $goal->present()->summary }}
					</div>
					<div class="panel-footer">
						<div class="visible-xs visible-sm">
							<div class="row">
								<div class="col-sm-4">
									<p><a href="#" class="btn btn-default btn-lg btn-block">View</a></p>
								</div>
								@if ($_currentUser->isStaff())
									<div class="col-sm-4">
										<p><a href="#" class="btn btn-default btn-lg btn-block">Edit</a></p>
									</div>
									<div class="col-sm-4">
										<p><a href="#" class="btn btn-danger btn-lg btn-block">Remove</a></p>
									</div>
								@endif
							</div>
						</div>
						<div class="visible-md visible-lg">
							<a href="#" class="btn btn-sm btn-default icn-size-16 pull-right">{{ $_icons['forward'] }}</a>
							@if ($_currentUser->isStaff())
								<a href="#" class="btn btn-sm btn-default icn-size-16">{{ $_icons['edit'] }}</a>
								<a href="#" class="btn btn-sm btn-danger icn-size-16">{{ $_icons['remove'] }}</a>
							@endif
						</div>
					</div>
				</div>
			</div>
		@endforeach
		</div>

		<hr>
	@endif

	{{ Form::open() }}
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">Add to the Conversation</label>
					{{ Form::textarea('content', null, ['class' => 'form-control', 'rows' => 3]) }}
				</div>
			</div>
			<div class="col-md-3">
				<label class="control-label visible-md visible-lg">&nbsp;</label>
				{{ Form::button("Submit", ['type' => 'submit', 'class' => 'btn btn-lg btn-block btn-primary']) }}
			</div>
		</div>
	{{ Form::close() }}

	{{ $plan->present()->conversation }}
@stop