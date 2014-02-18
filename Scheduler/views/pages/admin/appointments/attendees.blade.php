@if ($attendees->count() > 0)
	<div class="data-table data-table-striped data-table-bordered">
	@foreach ($attendees as $a)
		<div class="row">
			<div class="col-xs-12 col-sm-6 col-md-8 col-lg-8">
				<p><strong>{{ $a->user->name }}</strong></p>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
				<div class="visible-md visible-lg">
					<div class="btn-toolbar pull-right">
						@if ((bool) $a->paid === false)
							<div class="btn-group">
								<a href="#" class="btn btn-sm btn-primary icn-size-16 js-markAsPaid" data-appt="{{ $a->id }}">{{ $_icons['check'] }}</a>
							</div>
						@endif
						<div class="btn-group">
							<a href="#" class="btn btn-sm btn-danger icn-size-16">{{ $_icons['reject'] }}</a>
						</div>
					</div>
				</div>
				<div class="visible-xs visible-sm">
					<div class="row">
						<div class="col-xs-12 col-sm-6">
							@if ((bool) $a->paid === false)
								<p><a class="btn btn-lg btn-block btn-primary icn-size-16 js-markAsPaid" data-appt="{{ $a->id }}">{{ $_icons['check'] }}</a></p>
							@else
								<p class="hidden-xs visible-sm">&nbsp;</p>
							@endif
						</div>
						<div class="col-xs-12 col-sm-6">
							<p><a class="btn btn-lg btn-block btn-danger icn-size-16 js-appt-action">{{ $_icons['reject'] }}</a></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	@endforeach
	</div>
@else
	<div class="alert alert-warning">There are no attendees for this service.</div>
@endif

{{ View::make('partials.jsMarkAsPaid') }}
<script>

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