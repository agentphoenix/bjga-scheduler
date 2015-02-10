<form class="form-horizontal">
	@if ($stats->type == 'round')
		@if ( ! empty($stats->course))
			<div class="form-group">
				<label class="control-label col-sm-4">Course</label>
				<div class="col-sm-8">
					<p class="form-control-static">{{ $stats->present()->course }}</p>
				</div>
			</div>
		@endif

		@if ( ! empty($stats->score))
			<div class="form-group">
				<label class="control-label col-sm-4">Score</label>
				<div class="col-sm-8">
					<p class="form-control-static">{{ $stats->present()->score }}</p>
				</div>
			</div>
		@endif

		@if ( ! empty($stats->fir))
			<div class="form-group">
				<label class="control-label col-sm-4">Fairways</label>
				<div class="col-sm-8">
					<p class="form-control-static">{{ $stats->present()->fir }}</p>
				</div>
			</div>
		@endif

		@if ( ! empty($stats->gir))
			<div class="form-group">
				<label class="control-label col-sm-4">Greens</label>
				<div class="col-sm-8">
					<p class="form-control-static">{{ $stats->present()->gir }}</p>
				</div>
			</div>
		@endif

		@if ( ! empty($stats->putts))
			<div class="form-group">
				<label class="control-label col-sm-4">Putts</label>
				<div class="col-sm-8">
					<p class="form-control-static">{{ $stats->present()->putts }}</p>
				</div>
			</div>
		@endif

		@if ( ! empty($stats->penalties))
			<div class="form-group">
				<label class="control-label col-sm-4">Penalties</label>
				<div class="col-sm-8">
					<p class="form-control-static">{{ $stats->present()->penalties }}</p>
				</div>
			</div>
		@endif
	@endif

	@if ($stats->type == 'trackman')
		<div class="form-group">
			<label class="control-label col-sm-5">TrackMan Combine Score</label>
			<div class="col-sm-7">
				<p class="form-control-static">{{ $stats->present()->score }}</p>
			</div>
		</div>
	@endif
</form>

{{ $stats->present()->notes }}