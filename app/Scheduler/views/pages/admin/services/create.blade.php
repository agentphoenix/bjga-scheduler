@extends('layouts.master')

@section('title')
	Create Service
@endsection

@section('content')
	<h1>Create Service</h1>

	@if ($_currentUser->access() > 1)
		<div class="visible-lg">
			<div class="btn-toolbar">
				<div class="btn-group">
					<a href="{{ URL::route('admin.service.index') }}" class="btn btn-default icn-size-16">{{ $_icons['back'] }}</a>
				</div>
			</div>
		</div>
		<div class="hidden-lg">
			<div class="row">
				<div class="col-xs-12 col-sm-6">
					<p><a href="{{ URL::route('admin.service.index') }}" class="btn btn-block btn-lg btn-default icn-size-16">{{ $_icons['back'] }}</a></p>
				</div>
			</div>
		</div>
	@endif

	{{ Form::open(array('route' => 'admin.service.store')) }}
		<div class="row">
			<div class="col-lg-4">
				<div class="form-group{{ ($errors->has('category_id')) ? ' has-error' : '' }}">
					<label class="label-control">Category</label>
					{{ Form::select('category_id', $categories, null, array('class' => 'form-control')) }}
					{{ $errors->first('category_id', '<p class="help-block">:message</p>') }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-4">
				<div class="form-group{{ ($errors->has('staff_id')) ? ' has-error' : '' }}">
					<label class="label-control">Staff Member</label>
					{{ Form::select('staff_id', $staff, null, array('class' => 'form-control')) }}
					{{ $errors->first('staff_id', '<p class="help-block">:message</p>') }}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-4">
				<div class="form-group">
					<label class="label-control">Service Type</label>
					{{ Form::select('type', array('' => 'Choose a service type', 'OneToOne' => 'One occurrence, one participant', 'OneToMany' => 'One occurrence, many participants', 'ManyToMany' => 'Many occurrences, many participants'), null, array('class' => 'form-control js-service-type')) }}
				</div>
			</div>
		</div>

		<div id="ajax-container"></div>

		<div class="row hide" id="submitBtn">
			<div class="col-lg-12">
				<div class="visible-lg">
					{{ Form::button('Submit', array('type' => 'submit', 'class' => 'btn btn-primary')) }}
				</div>
				<div class="hidden-lg">
					{{ Form::button('Submit', array('type' => 'submit', 'class' => 'btn btn-lg btn-block btn-primary')) }}
				</div>
			</div>
		</div>
	{{ Form::close() }}
@endsection

@section('scripts')
	<script src="{{ URL::asset('js/bootstrap-datetimepicker.min.js') }}"></script>
	<script>
		$(document).on('change', '.js-service-type', function()
		{
			$.ajax({
				type: "POST",
				data: {
					'type': $('[name="type"] option:selected').val()
				},
				url: "{{ URL::route('ajax.createService') }}",
				success: function(data)
				{
					$('#ajax-container').html(data);
					$('#submitBtn').removeClass('hide');
				}
			});
		});

		$(document).on('click', '.js-addService-action', function(e)
		{
			e.preventDefault();

			$('#serviceDataTable .row:first').clone().find("input").each(function()
			{
				$(this).val('');
			}).end().appendTo('#serviceDataTable');
		});

		$(function()
		{
			$('.js-datepicker').datetimepicker({
				pickTime: false
			});
		});
	</script>
@endsection