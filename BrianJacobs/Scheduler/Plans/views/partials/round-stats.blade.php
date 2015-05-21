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

		@if ($stats->holes !== null)
			<div class="form-group">
				<label class="control-label col-sm-4">No. of Holes</label>
				<div class="col-sm-8">
					<p class="form-control-static">{{ $stats->present()->holes }}</p>
				</div>
			</div>
		@endif

		@if ($stats->score !== null)
			<div class="form-group">
				<label class="control-label col-sm-4">Score</label>
				<div class="col-sm-8">
					<p class="form-control-static">{{ $stats->present()->score }}</p>
				</div>
			</div>
		@endif

		@if ($stats->fir !== null)
			<div class="form-group">
				<label class="control-label col-sm-4">Fairways</label>
				<div class="col-sm-8">
					<p class="form-control-static">{{ $stats->present()->fir }}</p>
				</div>
			</div>
		@endif

		@if ($stats->gir !== null)
			<div class="form-group">
				<label class="control-label col-sm-4">Greens</label>
				<div class="col-sm-8">
					<p class="form-control-static">{{ $stats->present()->gir }}</p>
				</div>
			</div>
		@endif

		@if ($stats->putts !== null)
			<div class="form-group">
				<label class="control-label col-sm-4">Putts</label>
				<div class="col-sm-8">
					<p class="form-control-static">{{ $stats->present()->putts }}</p>
				</div>
			</div>
		@endif

		@if ($stats->penalties !== null)
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
			<label class="control-label col-sm-5">Combine Score</label>
			<div class="col-sm-7">
				<p class="form-control-static">{{ $stats->present()->score }}</p>
			</div>
		</div>
	@endif

	@if ($stats->type == 'practice')
		@if ($stats->minutes !== null)
			<div class="form-group">
				<label class="control-label col-sm-4">Minutes</label>
				<div class="col-sm-8">
					<p class="form-control-static">{{ $stats->present()->minutes }}</p>
				</div>
			</div>
		@endif

		@if ($stats->balls !== null)
			<div class="form-group">
				<label class="control-label col-sm-4">No. of Balls</label>
				<div class="col-sm-8">
					<p class="form-control-static">{{ $stats->present()->balls }}</p>
				</div>
			</div>
		@endif
	@endif
</form>

{{ $stats->present()->notes }}