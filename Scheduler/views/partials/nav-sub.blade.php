<nav class="nav-sub slideRight">
	<p>Menu</p>

	<ul>
		<li class="visible-xs visible-sm"><a href="http://brianjacobsgolf.com">Brian Jacobs Golf Home</a></li>
		@if (Auth::check())
			<li class="{{ (Request::is('/') ? 'active' : '') }}"><a href="{{ URL::route('home') }}"><span class="icn-size-16">{{ $_icons['schedule'] }}</span>My Schedule</a></li>
			<li class="{{ (Request::is('event*') ? 'active' : '') }}"><a href="{{ URL::route('events') }}"><span class="icn-size-16">{{ $_icons['calendar'] }}</span>Upcoming Programs</a></li>
			<li class="{{ (Request::is('admin/user/'.$_currentUser->id.'/edit') ? 'active' : '') }}"><a href="{{ URL::route('admin.user.edit', array($_currentUser->id)) }}"><span class="icn-size-16">{{ $_icons['user'] }}</span>My Account</a></li>
			<li><a href="{{ URL::route('logout') }}"><span class="icn-size-16">{{ $_icons['logout'] }}</span>Log Out</a></li>
		@else
			<li class="{{ (Request::is('/') ? 'active' : '') }}"><a href="{{ URL::route('home') }}"><span class="icn-size-16">{{ $_icons['login'] }}</span>Log In</a></li>
			<li class="{{ (Request::is('register') ? 'active' : '') }}"><a href="{{ URL::route('register') }}"><span class="icn-size-16">{{ $_icons['edit'] }}</span>Register</a></li>
		@endif
	</ul>

	@if (Auth::check())
		@if ($_currentUser->isStaff())
			<p>Admin</p>

			<ul>
				<li class="{{ (Request::is('admin/appointments*') ? 'active' : '') }}"><a href="{{ URL::route('admin.appointment.index') }}"><span class="icn-size-16">{{ $_icons['calendar'] }}</span>Appointments</a></li>
				<li class="{{ (Request::is('admin/service*') ? 'active' : '') }}"><a href="{{ URL::route('admin.service.index') }}"><span class="icn-size-16">{{ $_icons['golf'] }}</span>Services</a></li>
				<li class="{{ ((Request::is('admin/user*') and ! Request::is('admin/user/'.$_currentUser->id.'/edit')) ? 'active' : '') }}"><a href="{{ URL::route('admin.user.index') }}"><span class="icn-size-16">{{ $_icons['users'] }}</span>Users</a></li>
				<li class="{{ (Request::is('admin/staff*') ? 'active' : '') }}"><a href="{{ URL::route('admin.staff.index') }}"><span class="icn-size-16">{{ $_icons['school'] }}</span>Staff</a></li>
				<li class="{{ (Request::is('admin/reports*') ? 'active' : '') }}"><a href="{{ URL::route('admin.reports.index') }}"><span class="icn-size-16">{{ $_icons['report'] }}</span>Report Center</a></li>
			</ul>
		@endif

		<ul class="buttons">
			<li><a href="{{ URL::route('book.lesson') }}"><span class="icn-size-16">{{ $_icons['add'] }}</span>Book a Lesson</a></li>
			<li><a href="{{ URL::route('book.program') }}"><span class="icn-size-16">{{ $_icons['add'] }}</span>Enroll in Program</a></li>

			@if ($_currentUser->isStaff())
				<li><a href="{{ URL::route('admin.staff.schedule', array(Auth::user()->id)) }}"><span class="icn-size-16">{{ $_icons['calendar'] }}</span>Manage My Schedule</a></li>
			@endif
		</ul>
	@endif

	<div class="social">
		<div class="visible-xs visible-sm">
			<a href="https://www.facebook.com/brianjacobsgolf" target="_blank" class="btn btn-lg btn-default icn-size-16">{{ $_icons['facebook'] }}</a>
			<a href="https://twitter.com/BrianJacobsgolf" target="_blank" class="btn btn-lg btn-default icn-size-16">{{ $_icons['twitter'] }}</a>
		</div>
		<div class="visible-md visible-lg">
			<a href="https://www.facebook.com/brianjacobsgolf" target="_blank" class="btn btn-sm btn-default icn-size-16">{{ $_icons['facebook'] }}</a>
			<a href="https://twitter.com/BrianJacobsgolf" target="_blank" class="btn btn-sm btn-default icn-size-16">{{ $_icons['twitter'] }}</a>
		</div>
	</div>

	<div class="copyright">
		&copy; {{ Date::now()->year }} Brian Jacobs Golf
	</div>
</nav>