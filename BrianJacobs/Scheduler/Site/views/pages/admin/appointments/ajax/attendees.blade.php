@if ($attendees->count() > 0)
	<div class="data-table data-table-striped data-table-bordered">
	@foreach ($attendees as $a)
		<?php

		if ($a instanceof Scheduler\Data\Models\Eloquent\UserModel)
		{
			$user = $a;
			$apptBased = false;
		}
		else
		{
			$user = $a->user;
			$apptBased = true;
		}

		?>
		<div class="row">
			<div class="col-xs-12 col-sm-6 col-md-8">
				<p>
					<strong>{{ $user->name }}</strong>
					<br><span class="text-sm">Total Due: <strong>{{ $a->present()->due }}</strong></span>
				</p>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-4">
				<div class="visible-md visible-lg">
					<div class="btn-toolbar pull-right">
						@if ($apptBased and (bool) $a->paid === false)
							<div class="btn-group">
								<a href="#" class="btn btn-sm btn-primary icn-size-16 js-markAsPaid" data-appt="{{ $a->id }}">{{ $_icons['check'] }}</a>
							</div>
						@endif
						<div class="btn-group">
							<a href="#" class="btn btn-sm btn-danger icn-size-16 js-removeAttendee" data-appt="{{ $a->appointment_id }}" data-user="{{ $user->id }}">{{ $_icons['reject'] }}</a>
						</div>
					</div>
				</div>
				<div class="visible-xs visible-sm">
					<div class="row">
						<div class="col-xs-12 col-sm-6">
							@if ($apptBased and (bool) $a->paid === false)
								<p><a class="btn btn-lg btn-block btn-primary icn-size-16 js-markAsPaid" data-appt="{{ $a->id }}">Mark as Paid</a></p>
							@else
								<p class="hidden-xs visible-sm">&nbsp;</p>
							@endif
						</div>
						<div class="col-xs-12 col-sm-6">
							<p><a href="#" class="btn btn-lg btn-block btn-danger icn-size-16 js-removeAttendee" data-appt="{{ $a->appointment_id }}" data-user="{{ $user->id }}">Remove Attendee</a></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	@endforeach
	</div>
@else
	{{ alert('warning', "There are no attendees for this service.") }}
@endif

{{ View::make('partials.jsMarkAsPaid') }}
<script>
	$(document).on('click', '.js-removeAttendee', function(e)
	{
		e.preventDefault();

		var button = $(this);

		$.ajax({
			url: "{{ URL::to('admin/appointment/removeAttendee') }}",
			type: "POST",
			data: {
				appt: $(this).data('appt'),
				user: $(this).data('user')
			},
			success: function(data)
			{
				button.closest('.row').fadeOut('normal', function()
				{
					$(this).remove();
				});
			}
		});
	});
</script>