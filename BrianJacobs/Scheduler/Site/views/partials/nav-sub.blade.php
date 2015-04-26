<nav class="nav-sub slideRight">
	<p>Menu</p>
	<ul>
		<li class="visible-xs visible-sm"><a href="http://brianjacobsgolf.com">Brian Jacobs Golf Home</a></li>
		@if (Auth::check())
			<li class="{{ ((Request::is('/') or Request::is('days*')) ? 'active' : '') }}"><a href="{{ route('home') }}"><span class="icn-size-16">{{ $_icons['schedule'] }}</span>My Schedule</a></li>
			
			@if ($_currentUser->plan)
				<li class="{{ ((Request::is('my-plan*')) ? 'active' : '') }}"><a href="{{ route('my-plan') }}"><span class="icn-size-16">{{ $_icons['target'] }}</span>My Plan</a></li>
			@endif

			<li class="{{ ((Request::is('my-history')) ? 'active' : '') }}"><a href="{{ route('history') }}"><span class="icn-size-16">{{ $_icons['star'] }}</span>My History</a></li>
		@endif
			
		<li class="{{ (Request::is('event*') ? 'active' : '') }}"><a href="{{ route('events') }}"><span class="icn-size-16">{{ $_icons['calendar'] }}</span>Upcoming Programs</a></li>
		<li class="{{ (Request::is('locations') ? 'active' : '') }}"><a href="{{ route('locations') }}"><span class="icn-size-16">{{ $_icons['map'] }}</span>Our Locations</a></li>

		@if (Auth::check())
			<li class="{{ (Request::is('admin/user/'.$_currentUser->id.'/edit') ? 'active' : '') }}"><a href="{{ route('admin.user.edit', array($_currentUser->id)) }}"><span class="icn-size-16">{{ $_icons['user'] }}</span>My Account</a></li>

			@if ($_currentUser->isStaff())
				<li><a href="{{ route('admin.staff.schedule', array(Auth::user()->staff->id)) }}"><span class="icn-size-16">{{ $_icons['calendar'] }}</span>Manage Schedule</a></li>
			@endif
			
			<li><a href="{{ route('logout') }}"><span class="icn-size-16">{{ $_icons['logout'] }}</span>Log Out</a></li>
		@else
			<li class="{{ (Request::is('/') ? 'active' : '') }}"><a href="{{ route('home') }}"><span class="icn-size-16">{{ $_icons['login'] }}</span>Log In</a></li>
			<li class="{{ (Request::is('register') ? 'active' : '') }}"><a href="{{ route('register') }}"><span class="icn-size-16">{{ $_icons['edit'] }}</span>Register</a></li>
		@endif
	</ul>

	@if (Auth::check())
		@if ($_currentUser->getCredits()['time'] > 0 or $_currentUser->getCredits()['money'] > 0)
			<p>My Credit</p>
			<ul>
				@if ($_currentUser->getCredits()['time'] > 0)
					<li><div class="text"><span class="icn-size-16">{{ $_icons['clock'] }}</span> {{ $_currentUser->present()->creditTime }}</div></li>
				@endif
				@if ($_currentUser->getCredits()['money'] > 0)
					<li><div class="text"><span class="icn-size-16">{{ $_icons['credit'] }}</span>{{ $_currentUser->present()->creditMoney }}</div></li>
				@endif
			</ul>
		@endif
		
		@if ($_currentUser->isStaff())
			<p>Admin</p>
			<ul>
				<li class="{{ (Request::is('admin/appointment*') ? 'active' : '') }}"><a href="{{ route('admin.appointment.index') }}"><span class="icn-size-16">{{ $_icons['calendar'] }}</span>Appointments</a></li>
				<li class="{{ (Request::is('admin/service*') ? 'active' : '') }}"><a href="{{ route('admin.service.index') }}"><span class="icn-size-16">{{ $_icons['golf'] }}</span>Services</a></li>

				@if ($_currentUser->access() > 1)
					<li class="{{ ((Request::is('admin/user*') and ! Request::is('admin/user/'.$_currentUser->id.'/edit')) ? 'active' : '') }}"><a href="{{ route('admin.user.index') }}"><span class="icn-size-16">{{ $_icons['users'] }}</span>Users</a></li>
					<li class="{{ (Request::is('admin/staff*') ? 'active' : '') }}"><a href="{{ route('admin.staff.index') }}"><span class="icn-size-16">{{ $_icons['school'] }}</span>Staff</a></li>
					
					@if ($_currentUser->access() >= 3)
						<li class="{{ (Request::is('admin/plan*') ? 'active' : '') }}"><a href="{{ route('admin.plan.index') }}"><span class="icn-size-16">{{ $_icons['list'] }}</span>Development Plans</a></li>
					@endif
					
					<li class="{{ (Request::is('admin/locations*') ? 'active' : '') }}"><a href="{{ route('admin.locations.index') }}"><span class="icn-size-16">{{ $_icons['map'] }}</span>Locations</a></li>
				@endif

				<li class="{{ (Request::is('admin/credits*') ? 'active' : '') }}"><a href="{{ route('admin.credits.index') }}"><span class="icn-size-16">{{ $_icons['money'] }}</span>Credits</a></li>
				<li class="{{ (Request::is('admin/reports*') ? 'active' : '') }}"><a href="{{ route('admin.reports.index') }}"><span class="icn-size-16">{{ $_icons['report'] }}</span>Report Center</a></li>
			</ul>
		@endif

		<ul class="buttons">
			<li><a href="{{ route('book.lesson') }}"><span class="icn-size-16">{{ $_icons['add'] }}</span>Book a Lesson</a></li>
			<li><a href="{{ route('book.program') }}"><span class="icn-size-16">{{ $_icons['add'] }}</span>Enroll in Program</a></li>
			<li><a href="{{ route('search') }}"><span class="icn-size-16">{{ $_icons['search'] }}</span>Find Lesson Time</a></li>
			<li><a href="#" data-toggle="modal" data-target="#applyCredit"><span class="icn-size-16">{{ $_icons['credit'] }}</span>Apply User Credit</a></li>
			<li><a href="#" data-toggle="modal" data-target="#reportProblem"><span class="icn-size-16">{{ $_icons['warning'] }}</span>Report a Problem</a></li>
		</ul>
	@endif

	<div class="social">
		<div class="visible-xs visible-sm">
			<a href="https://www.facebook.com/brianjacobsgolf" target="_blank" class="btn btn-lg btn-default icn-size-16">{{ $_icons['facebook'] }}</a>
			<a href="https://twitter.com/BrianJacobsgolf" target="_blank" class="btn btn-lg btn-default icn-size-16">{{ $_icons['twitter'] }}</a>
			<a href="http://instagram.com/BrianJacobsgolf" target="_blank" class="btn btn-lg btn-default icn-size-16">{{ $_icons['instagram'] }}</a>
		</div>
		<div class="visible-md visible-lg">
			<a href="https://www.facebook.com/brianjacobsgolf" target="_blank" class="btn btn-sm btn-default icn-size-16">{{ $_icons['facebook'] }}</a>
			<a href="https://twitter.com/BrianJacobsgolf" target="_blank" class="btn btn-sm btn-default icn-size-16">{{ $_icons['twitter'] }}</a>
			<a href="http://instagram.com/BrianJacobsgolf" target="_blank" class="btn btn-sm btn-default icn-size-16">{{ $_icons['instagram'] }}</a>
		</div>
	</div>

	<div class="copyright">
		&copy; {{ Date::now()->year }} Brian Jacobs Golf
	</div>
</nav>