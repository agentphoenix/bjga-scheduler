@extends('layouts.master')

@section('title')
	Users
@endsection

@section('content')
	<h1>Users</h1>

	<div class="visible-lg">
		<div class="btn-toolbar">
			<div class="btn-group">
				<a href="{{ URL::route('admin') }}" class="btn btn-sm btn-default icn-size-16">{{ $_icons['back'] }}</a>
			</div>
			<div class="btn-group">
				<a href="{{ URL::route('admin.user.create') }}" class="btn btn-sm btn-primary icn-size-16">{{ $_icons['add'] }}</a>
			</div>
		</div>
	</div>
	<div class="hidden-lg">
		<div class="row">
			<div class="col-xs-6 col-sm-6">
				<p><a href="{{ URL::route('admin') }}" class="btn btn-block btn-lg btn-default icn-size-16">{{ $_icons['back'] }}</a></p>
			</div>
			<div class="col-xs-6 col-sm-6">
				<p><a href="{{ URL::route('admin.user.create') }}" class="btn btn-block btn-lg btn-primary icn-size-16">{{ $_icons['add'] }}</a></p>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
			{{ Form::text('search', null, array('placeholder' => 'Search for users', 'class' => 'form-control search-control', 'id' => 'searchUsers')) }}
		</div>
	</div>

	<div class="data-table data-table-striped data-table-bordered" id="usersTable">
	@foreach ($users as $user)
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-lg-8">
				<p><strong>{{ $user->name }}</strong></p>
				<p class="text-muted text-small">{{ $user->email }}</p>
			</div>
			<div class="col-xs-12 col-sm-12 col-lg-4">
				<div class="visible-lg">
					<div class="btn-toolbar pull-right">
						@if ( ! empty($user->email))
							<div class="btn-group">
								<a href="tel:{{ $user->email }}" class="btn btn-sm btn-default icn-size-16">{{ $_icons['email'] }}</a>
							</div>
						@endif

						<div class="btn-group">
							<a href="{{ URL::route('admin.user.edit', array($user->id)) }}" class="btn btn-sm btn-default icn-size-16">{{ $_icons['edit'] }}</a>
						</div>

						<div class="btn-group">
							<a href="{{ URL::route('admin.user.destroy', array($user->id)) }}" class="btn btn-sm btn-danger icn-size-16 js-user-action" data-action="delete" data-id="{{ $user->id }}">{{ $_icons['remove'] }}</a>
						</div>
					</div>
				</div>
				<div class="hidden-lg">
					<div class="row">
						@if ( ! empty($user->phone))
							<div class="col-xs-12 visible-xs">
								<p><a href="tel:{{ $user->phone }}" class="btn btn-block btn-lg btn-default icn-size-16">{{ $_icons['phone'] }}</a></p>
							</div>
						@endif

						@if ( ! empty($user->email))
							<div class="col-xs-12 visible-xs">
								<p><a href="tel:{{ $user->email }}" class="btn btn-block btn-lg btn-default icn-size-16">{{ $_icons['email'] }}</a></p>
							</div>
						@endif

						<div class="col-xs-6 col-sm-6">
							<p><a href="{{ URL::route('admin.user.edit', array($user->id)) }}" class="btn btn-block btn-lg btn-default icn-size-16">{{ $_icons['edit'] }}</a></p>
						</div>

						<div class="col-xs-6 col-sm-6">
							<p><a href="{{ URL::route('admin.user.destroy', array($user->id)) }}" class="btn btn-block btn-lg btn-danger icn-size-16 js-user-action" data-action="delete" data-id="{{ $user->id }}">{{ $_icons['remove'] }}</a></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	@endforeach
	</div>

	{{ modal(array('id' => 'deleteUser', 'header' => "Delete User")) }}
@endsection

@section('scripts')
	{{ HTML::script('js/jquery.quicksearch.min.js') }}
	<script>

		$('#searchUsers').quicksearch('#usersTable > div', {
			hide: function()
			{
				$(this).addClass('hide');
			},
			show: function()
			{
				$(this).removeClass('hide');
			}
		});
		
		$('.js-user-action').on('click', function(e)
		{
			e.preventDefault();

			var action = $(this).data('action');
			var id = $(this).data('id');

			if (action == 'delete')
			{
				$('#deleteUser').modal({
					remote: "{{ URL::to('ajax/user/delete') }}/" + id
				}).modal('show');
			}
		});

	</script>
@endsection