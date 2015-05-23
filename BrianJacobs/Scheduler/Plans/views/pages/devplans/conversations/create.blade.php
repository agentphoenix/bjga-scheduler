{{ Form::open(['route' => ['conversation.store', $goal->id], 'class' => 'form-horizontal']) }}
	<div class="form-group">
		<label class="control-label col-md-2">Comment</label>
		<div class="col-md-10">
			{{ Form::textarea('content', null, ['class' => 'form-control input-lg', 'rows' => 5]) }}
		</div>
	</div>

	{{ Form::hidden('goal_id', $goal->id) }}

	<div class="form-group">
		<div class="col-md-8 col-md-offset-2">
			<div class="visible-xs visible-sm">
				{{ Form::button("Add Comment", ['type' => 'submit', 'class' => 'btn btn-primary btn-lg btn-block']) }}
			</div>
			<div class="visible-md visible-lg">
				{{ Form::button("Add Comment", ['type' => 'submit', 'class' => 'btn btn-primary btn-lg']) }}
			</div>
		</div>
	</div>
{{ Form::close() }}