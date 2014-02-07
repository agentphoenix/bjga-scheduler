@extends('layouts.ajax')

@section('content')
	@if ($attendees->count() > 0)
		<div class="data-table data-table-striped data-table-bordered">
		@foreach ($attendees as $a)
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-lg-8">
					<p><strong>{{ $a->name }}</strong></p>
				</div>
				<div class="col-xs-12 col-sm-12 col-lg-4">
					<div class="row">
						<div class="col-lg-6">
							<p><a class="btn btn-lg btn-block btn-default icn-size-16 js-appt-action">{{ $_icons['star'] }}</a></p>
						</div>

						@if ($_currentUser->access() >= 2)
							<div class="col-lg-6">
								<p><a class="btn btn-lg btn-block btn-danger icn-size-16 js-appt-action" data-action="delete" data-id="{{ $service->id }}">{{ $_icons['remove'] }}</a></p>
							</div>
						@endif
					</div>
				</div>
			</div>
		@endforeach
		</div>
	@else
		<div class="alert alert-warning">There are no attendees for this service.</div>
	@endif
@endsection

@section('scripts')
	<script type="text/javascript">

		$(document).on('click', '.js-service-action', function(e)
		{
			e.preventDefault();

			var action = $(this).data('action');
			var id = $(this).data('id');

			if (action == 'delete')
			{
				$('#deleteService').modal({
					remote: "{{ URL::to('ajax/service/delete') }}/" + id
				}).modal('show');
			}
		});

	</script>
@endsection