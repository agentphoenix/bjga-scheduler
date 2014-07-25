<h2>Total</h2>

<div class="data-table data-table-bordered data-table-striped">
	<div class="row">
		<div class="col-xs-6 col-sm-7"><p>
			{{ $service->name }}
			@if ($service->isRecurring())
				<br><span class="text-sm">{{ $service->occurrences }} appointments</span>
			@endif
		</p></div>
		<div class="col-xs-6 col-sm-5 text-right"><p><strong>
			@if ($service->isRecurring())
				${{ number_format($service->price) }} <span class="text-sm">per appt.</span>
			@else
				{{ $service->present()->price }}
			@endif
		</strong></p></div>
	</div>

	@if ($user->isStaff())
		<div class="row">
			<div class="col-xs-6 col-sm-7 text-danger"><p>Staff Discount</p></div>
			<div class="col-xs-6 col-sm-5 text-right text-danger"><p><strong>100%</strong></p></div>
		</div>
	@else
		@if ($credits['time'] > 0)
			<div class="row">
				<div class="col-xs-6 col-sm-7 text-danger"><p>Time Credit Applied</p></div>
				<div class="col-xs-6 col-sm-5 text-right text-danger"><p><strong>
					@if ($service->isRecurring())
						<?php $niceDuration = round($service->duration * $service->occurrences / 60, 2);?>
						@if ($credits['time'] >= $service->duration * $service->occurrences)
							@if ($niceDuration == 1)
								{{ $niceDuration }} hour
							@elseif ($niceDuration > 1)
								{{ $niceDuration }} hours
							@else
								{{ $service->duration }} minutes
							@endif
						@else
							@if ($credits['time'] / 60 == 1)
								{{ round($credits['time'] / 60, 2) }} hour
							@elseif ($credits['time'] / 60 > 1)
								{{ round($credits['time'] / 60, 2) }} hours
							@else
								{{ $credits['time'] }} minutes
							@endif
						@endif
					@else
						<?php $niceDuration = round($service->duration / 60, 2);?>
						@if ($credits['time'] >= $service->duration)
							@if ($niceDuration == 1)
								{{ $niceDuration }} hour
							@elseif ($niceDuration > 1)
								{{ $niceDuration }} hours
							@else
								{{ $service->duration }} minutes
							@endif
						@else
							@if ($credits['time'] / 60 == 1)
								{{ round($credits['time'] / 60, 2) }} hour
							@elseif ($credits['time'] / 60 > 1)
								{{ round($credits['time'] / 60, 2) }} hours
							@else
								{{ $credits['time'] }} minutes
							@endif
						@endif
					@endif
				</strong></p></div>
			</div>
		@endif

		@if ($credits['money'] > 0 and ( ! $service->isRecurring() and $credits['time'] < $service->duration) or ($service->isRecurring() and $credits['time'] < $service->duration * $service->occurrences))
			<div class="row">
				<div class="col-xs-6 col-sm-7 text-danger"><p>Monetary Credit Applied</p></div>
				<div class="col-xs-6 col-sm-5 text-right text-danger"><p><strong>
					@if ($service->isRecurring())
						@if ($credits['money'] >= $service->price * $service->occurrences)
							${{ $service->price }}
						@else
							${{ $credits['money'] }}
						@endif
					@else
						@if ($credits['money'] >= $service->price)
							${{ $service->price }}
						@else
							${{ $credits['money'] }}
						@endif
					@endif
				</strong></p></div>
			</div>
		@endif
	@endif

	<div class="row">
		<div class="col-xs-6 col-sm-7"><p>
			Total
			@if ($service->isRecurring())
				<br><span class="text-sm text-muted"><em>Estimate; actual per appt. will vary</em></span>
			@endif
		</p></div>
		<div class="col-xs-6 col-sm-5 text-right"><p><strong>
			@if ($service->isRecurring())
				{{ $total }} <span class="text-sm">per appt.</span>
			@else
				{{ $total }}
			@endif
		</strong></p></div>
	</div>
</div>