<nav class="nav-main hidden-xs">
	<div class="brand"></div>
	
	<ul>
		<li><a href="http://brianjacobsgolf.com">About Us</a></li>
		<li><a href="http://brianjacobsgolf.com/instruction">Instruction</a></li>
		<li><a href="http://brianjacobsgolf.com/contact/general">Contact</a></li>
		<li><a href="{{ URL::route('home') }}" class="active">Book Now</a></li>
	</ul>
</nav>

<div class="visible-xs">
	<div class="navbar navbar-default navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>

				<a href="{{ URL::route('home') }}" class="navbar-brand">Brian Jacobs Golf</a>
			</div>

			<div class="navbar-collapse collapse navbar-responsive-collapse">
				<ul class="nav navbar-nav">
					<li><a href="{{ URL::route('home') }}">Home</a></li>

					@if (Auth::check())
						<li><a href="{{ URL::route('book.index') }}">Book Appointment</a></li>
						<li><a href="#">My Account</a></li>

						@if (Auth::user()->isStaff())
							<li><a href="{{ URL::route('admin') }}">Admin</a></li>			
						@endif

						<li><a href="{{ URL::route('logout') }}">Log Out</a></li>
					@else
						<li><a href="{{ URL::route('register') }}">Register</a></li>
					@endif
					
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">Brian Jacobs Golf <b class="caret"></b></a>

						<ul class="dropdown-menu">
							<li><a href="http://brianjacobsgolf.com">About Us</a></li>
							<li><a href="http://brianjacobsgolf.com/instruction">Instruction</a></li>
							<li><a href="http://brianjacobsgolf.com/contact/general">Contact</a></li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>