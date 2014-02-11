<div class="slideLeft">
	<p><strong>Menu</strong></p>

	<ul>
		@if (Auth::check())
			<li class="{{ (Request::is('/') ? 'active' : '') }}"><a href="{{ URL::route('home') }}"><span class="icn-size-16">{{ $_icons['schedule'] }}</span>My Schedule</a></li>
			<li class="{{ (Request::is('admin/user/*') ? 'active' : '') }}"><a href="{{ URL::route('admin.user.edit', array($_currentUser->id)) }}"><span class="icn-size-16">{{ $_icons['user'] }}</span>My Account</a></li>
			<li><a href="{{ URL::route('logout') }}"><span class="icn-size-16">{{ $_icons['logout'] }}</span>Log Out</a></li>
		@else
			<li class="{{ (Request::is('/') ? 'active' : '') }}"><a href="{{ URL::route('home') }}"><span class="icn-size-16">{{ $_icons['calendar'] }}</span>Log In</a></li>
			<li class="{{ (Request::is('register') ? 'active' : '') }}"><a href="{{ URL::route('register') }}"><span class="icn-size-16">{{ $_icons['calendar'] }}</span>Register</a></li>
		@endif
	</ul>

	@if (Auth::check())
		@if ($_currentUser->isStaff())
			<p><strong>Admin</strong></p>

			<ul>
				<li class="{{ (Request::is('admin/appointments*') ? 'active' : '') }}"><a href="#"><span class="icn-size-16">{{ $_icons['calendar'] }}</span>Appointments</a></li>
				<li class="{{ (Request::is('admin/service*') ? 'active' : '') }}"><a href="{{ URL::route('admin.service.index') }}"><span class="icn-size-16">{{ $_icons['golf'] }}</span>Services</a></li>
				<li class="{{ (Request::is('admin/user*') ? 'active' : '') }}"><a href="{{ URL::route('admin.user.index') }}"><span class="icn-size-16">{{ $_icons['users'] }}</span>Users</a></li>
				<li class="{{ (Request::is('admin/staff*') ? 'active' : '') }}"><a href="{{ URL::route('admin.staff.index') }}"><span class="icn-size-16">{{ $_icons['school'] }}</span>Staff</a></li>
				<li class="{{ (Request::is('admin/reports*') ? 'active' : '') }}"><a href="{{ URL::route('admin.reports.index') }}"><span class="icn-size-16">{{ $_icons['report'] }}</span>Report Center</a></li>
			</ul>
		@endif

		<ul class="buttons">
			<li><a href="{{ URL::route('book.lesson') }}"><span class="icn-size-16">{{ $_icons['add'] }}</span>Book a Lesson</a></li>
			<li><a href="{{ URL::route('book.program') }}"><span class="icn-size-16">{{ $_icons['add'] }}</span>Enroll in Program</a></li>

			@if ($_currentUser->isStaff())
				<li><a href="{{ URL::route('admin.staff.block') }}"><span class="icn-size-16">{{ $_icons['reject'] }}</span>Block My Calendar</a></li>
			@endif
		</ul>
	@endif
</div>