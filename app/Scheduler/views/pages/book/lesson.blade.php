@extends('layouts.master')

@section('title')
	Book Private Instruction
@endsection

@section('content')
	<h1>Book Private Instruction</h1>

	{{ Form::open() }}
		<div class="row">
			<div class="col-lg-4">
				<div class="form-group">
					<label class="control-label">Service</label>
					<div class="controls">
						{{ Form::select('service_id', $services, null, array('class' => 'form-control')) }}
						<div id="serviceDescription"></div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-2">
				<div class="form-group">
					<label class="control-label">Date</label>
					<div class="controls">
						{{ Form::text('date', '2013-07-29', array('class' => 'form-control')) }}
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-4">
				<div class="form-group">
					<div class="visible-lg">
						<a href="{{ URL::to('ajax/availability') }}" class="btn btn-primary" id="checkAvailability">Check Availability</a>
					</div>
					<div class="hidden-lg">
						<p><a href="{{ URL::to('ajax/availability') }}" class="btn btn-block btn-primary" id="checkAvailability">Check Availability</a></p>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<div class="form-group">
					<div id="ajax-container"></div>
				</div>
			</div>
		</div>
	{{ Form::close() }}
@endsection

@section('scripts')
	<script type="text/javascript">
		
		$(document).on('click', '#checkAvailability', function(e)
		{
			e.preventDefault();

			$.ajax({
				data: {
					'service': $('[name="service_id"] option:selected').val(),
					'date': $('[name="date"]').val()
				},
				url: this.href,
				success: function(data)
				{
					$('#ajax-container').html(data);
				}
			});
		});

	</script>
@endsection