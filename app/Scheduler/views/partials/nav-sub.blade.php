<nav class="nav-sub hidden-xs">
	<ul class="social-icons">
		<li><a href="https://www.facebook.com/brianjacobsgolf" target="_blank" class="icn-size-24"><span class="icn" data-icon="c"></span></a></li>
		<li><a href="http://twitter.com/BrianJacobsgolf" target="_blank" class="icn-size-24"><span class="icn" data-icon="w"></span></a></li>
	</ul>

	<ul>
		<li class="{{ Request::is('/') ? 'active' : '' }}"><a href="{{ URL::route('home') }}">Home</a><div class="arrow"></div></li>

		@if (Auth::check())
			<li class="{{ Request::is('book') ? 'active' : '' }}"><a href="{{ URL::route('book.index') }}">Book Appointment</a><div class="arrow"></div></li>
			<li><a href="{{ URL::route('admin.user.edit', array(Auth::user()->id)) }}">My Account</a><div class="arrow"></div></li>

			@if (Auth::user()->isStaff())
				<li class="{{ Request::is('admin*') ? 'active' : '' }}"><a href="{{ URL::route('admin') }}">Admin</a><div class="arrow"></div></li>			
			@endif
		@else
			<li class="{{ Request::is('register') ? 'active' : '' }}"><a href="{{ URL::route('register') }}">Register</a><div class="arrow"></div></li>
		@endif

		<li><a href="{{ URL::route('logout') }}">Log Out</a><div class="arrow"></div></li>
	</ul>
</nav>