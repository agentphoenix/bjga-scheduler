@extends('layouts.master')

@section('title')
	Create Credit
@stop

@section('content')
	<h1>Create Credit</h1>

	@if ($_currentUser->access() > 1)
		<div class="visible-md visible-lg">
			<div class="btn-toolbar">
				<div class="btn-group">
					<a href="{{ URL::route('admin.credits.index') }}" class="btn btn-sm btn-default icn-size-16">{{ $_icons['back'] }}</a>
				</div>
			</div>
		</div>
		<div class="visible-xs visible-sm">
			<div class="row">
				<div class="col-xs-6 col-sm-3">
					<p><a href="{{ URL::route('admin.credits.index') }}" class="btn btn-block btn-lg btn-default icn-size-16">{{ $_icons['back'] }}</a></p>
				</div>
			</div>
		</div>
	@endif

	{{ Form::open(['route' => 'admin.credits.store']) }}
		<div class="row">
			<div class="col-sm-6 col-lg-4">
				<div class="form-group">
					<label class="control-label">Code</label>
					<p class="form-control-static"><code class="text-lg">{{ $code }}</code></p>
					{{ Form::hidden('code', $code) }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-6 col-md-4 col-lg-2">
				<div class="form-group">
					<label class="control-label">Credit Type</label>
					{{ Form::select('type', $types, null, ['class' => 'form-control']) }}
				</div>
			</div>

			<div class="col-sm-6 col-md-4 col-lg-2">
				<div class="form-group">
					<label class="control-label">Value</label>
					<div id="moneyType" class="input-group hide">
						<span class="input-group-addon" id="moneyType"><strong>$</strong></span>
						{{ Form::text('valueMoney', null, ['class' => 'form-control']) }}
					</div>
					<div id="timeType" class="input-group">
						{{ Form::text('valueTime', null, ['class' => 'form-control']) }}
						<span class="input-group-addon"><strong>hours</strong></span>
					</div>

					{{ Form::hidden('value', '') }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-8 col-md-6 col-lg-4">
				<div class="form-group">
					<label class="control-label">User <span class="text-sm">(Optional)</span></label>
					{{ Form::select('user_id', $users, null, ['class' => 'form-control']) }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-8 col-md-6 col-lg-4">
				<div class="form-group">
					<label class="control-label">Email Address <span class="text-sm">(Optional)</span></label>
					{{ Form::email('email', null, ['class' => 'form-control']) }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12">
				<div class="visible-md visible-lg">
					{{ Form::submit('Create', array('class' => 'btn btn-lg btn-primary')) }}
				</div>
				<div class="visible-xs visible-sm">
					{{ Form::submit('Create', array('class' => 'btn btn-lg btn-block btn-primary')) }}
				</div>
			</div>
		</div>
	{{ Form::close() }}
@stop

@section('scripts')
	<script>
		$('[name="valueMoney"]').on('change', function()
		{
			$('[name="value"]').val($(this).val());
		});

		$('[name="valueTime"]').on('change', function()
		{
			$('[name="value"]').val($(this).val());
		});

		$('[name="type"]').on('change', function()
		{
			var type = $('[name="type"] option:selected').val();

			if (type == 'time')
			{
				$('#moneyType').addClass('hide');
				$('#timeType').removeClass('hide');

				$('[name="valueTime"]').val('');
				$('[name="valueMoney"]').val('');
				$('[name="value"]').val('');
			}

			if (type == 'money')
			{
				$('#timeType').addClass('hide');
				$('#moneyType').removeClass('hide');

				$('[name="valueTime"]').val('');
				$('[name="valueMoney"]').val('');
				$('[name="value"]').val('');
			}
		});
	</script>
@stop