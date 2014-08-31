@extends('layouts.master')

@section('title')
	Report Center - Monthly Report
@stop

@section('content')
	<h1>Monthly Report</h1>

	<div class="visible-md visible-lg">
		<div class="btn-toolbar">
			<div class="btn-group">
				<a href="{{ URL::route('admin.reports.index') }}" class="btn btn-sm btn-default icn-size-16">{{ $_icons['back'] }}</a>
			</div>
		</div>
	</div>
	<div class="visible-xs visible-sm">
		<div class="row">
			<div class="col-xs-6 col-sm-3">
				<p><a href="{{ URL::route('admin.reports.index') }}" class="btn btn-lg btn-block btn-default icn-size-16">{{ $_icons['back'] }}</a></p>
			</div>
		</div>
	</div>

	{{ Form::open(array('route' => array('admin.reports.monthly.update'))) }}
		<div class="row">
			<div class="col-xs-7 col-sm-4 col-md-4 col-lg-4">
				<div class="form-group">
					<label class="control-label">Select a Month</label>
					{{ Form::select('date', $options, null, array('class' => 'form-control')) }}
				</div>
			</div>
			<div class="col-xs-5 col-sm-2 col-md-2 col-lg-2">
				<div class="form-group">
					<label class="control-label">&nbsp;</label>
					{{ Form::submit('Get Report', array('class' => 'btn btn-sm btn-block btn-primary')) }}
				</div>
			</div>
		</div>
	{{ Form::close() }}

	<div class="page-header">
		<h2>{{ $date->format('F Y') }}</h2>
	</div>

	<div class="row">
		<div class="col-sm-6 col-md-6 col-lg-6">
			<div class="well well-sm text-center">
				<h3>Total Revenue</h3>
				<p class="lead"><strong>${{ number_format($revenue, 2) }}</strong>
			</div>
		</div>
		<div class="col-sm-6 col-md-6 col-lg-6">
			<div class="well well-sm text-center">
				<h3>Revenue YTD</h3>
				<p class="lead"><strong>${{ number_format($revenueYTD, 2) }}</strong>
			</div>
		</div>

		<div class="col-sm-6 col-md-6 col-lg-6">
			<div class="well well-sm text-center">
				<h3>New Students</h3>
				<p class="lead"><strong>{{ $newStudents }}</strong>
			</div>
		</div>
		<div class="col-sm-6 col-md-6 col-lg-6">
			<div class="well well-sm text-center">
				<h3>New Students YTD</h3>
				<p class="lead"><strong>{{ $newStudentsYTD }}</strong>
			</div>
		</div>

		<div class="col-sm-6 col-md-6 col-lg-6">
			<div class="well well-sm text-center">
				<h3>Students Seen</h3>
				<p class="lead"><strong>{{ $students }}</strong>
			</div>
		</div>
		<div class="col-sm-6 col-md-6 col-lg-6">
			<div class="well well-sm text-center">
				<h3>Students Seen YTD</h3>
				<p class="lead"><strong>{{ $studentsYTD }}</strong>
			</div>
		</div>

		<div class="col-sm-6 col-md-6 col-lg-6">
			<div class="well well-sm text-center">
				<h3>Revenue/Student</h3>
				@if ($students > 0)
					<p class="lead"><strong>${{ number_format($revenue / $students, 2) }}</strong>
				@else
					<p class="lead text-muted"><strong>&mdash;</strong></p>
				@endif
			</div>
		</div>
		<div class="col-sm-6 col-md-6 col-lg-6">
			<div class="well well-sm text-center">
				<h3>Revenue/Student YTD</h3>
				@if ($studentsYTD > 0)
					<p class="lead"><strong>${{ number_format($revenueYTD / $studentsYTD, 2) }}</strong>
				@else
					<p class="lead text-muted"><strong>&mdash;</strong></p>
				@endif
			</div>
		</div>

		<div class="col-sm-6 col-md-6 col-lg-6">
			<div class="well well-sm text-center">
				<h3>Lesson Hours</h3>
				<p class="lead"><strong>{{ $hours }} hours</strong>
			</div>
		</div>
		<div class="col-sm-6 col-md-6 col-lg-6">
			<div class="well well-sm text-center">
				<h3>Lesson Hours YTD</h3>
				<p class="lead"><strong>{{ $hoursYTD }} hours</strong>
			</div>
		</div>

		<div class="col-sm-6 col-md-6 col-lg-6">
			<div class="well well-sm text-center">
				<h3>Revenue/Hour</h3>
				@if ($hours > 0)
					<p class="lead"><strong>${{ number_format($revenue / $hours, 2) }}</strong>
				@else
					<p class="lead text-muted"><strong>&mdash;</strong></p>
				@endif
			</div>
		</div>
		<div class="col-sm-6 col-md-6 col-lg-6">
			<div class="well well-sm text-center">
				<h3>Revenue/Hour YTD</h3>
				@if ($hoursYTD > 0)
					<p class="lead"><strong>${{ number_format($revenueYTD / $hoursYTD, 2) }}</strong>
				@else
					<p class="lead text-muted"><strong>&mdash;</strong></p>
				@endif
			</div>
		</div>
	</div>
@stop