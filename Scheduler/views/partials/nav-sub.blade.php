<nav class="nav-sub hidden-xs">
	<ul>
		<li class="{{ (Request::is('/') or Request::is('book/*')) ? 'active' : '' }}"><a href="{{ URL::route('home') }}">Home</a><div class="arrow"></div></li>

		@if (Auth::check())
			<li><a href="{{ URL::route('admin.user.edit', array(Auth::user()->id)) }}">My Account</a><div class="arrow"></div></li>

			@if (Auth::user()->isStaff())
				<li class="{{ Request::is('admin*') ? 'active' : '' }}"><a href="{{ URL::route('admin') }}">Admin</a><div class="arrow"></div></li>			
			@endif

			<li><a href="{{ URL::route('logout') }}">Log Out</a><div class="arrow"></div></li>
		@else
			<li class="{{ Request::is('register') ? 'active' : '' }}"><a href="{{ URL::route('register') }}">Register</a><div class="arrow"></div></li>
		@endif
	</ul>
</nav>