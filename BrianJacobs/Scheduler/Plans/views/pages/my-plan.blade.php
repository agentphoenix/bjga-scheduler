@extends('layouts.master')

@section('title')
	My Development Plan
@stop

@section('content')
	<h1>My Development Plan</h1>

	<div class="visible-xs visible-sm">
		<div class="row">
			<div class="col-xs-6 col-sm-3">
				<p><a href="{{ route('admin.credits.create') }}" class="btn btn-block btn-lg btn-primary icn-size-16">Add Goal</a></p>
			</div>
		</div>
	</div>
	<div class="visible-md visible-lg">
		<div class="btn-toolbar">
			<div class="btn-group">
				<a href="{{ route('admin.credits.create') }}" class="btn btn-sm btn-primary icn-size-16">{{ $_icons['add'] }}</a>
			</div>
		</div>
	</div>

	<section id="cd-timeline" class="cd-container">
		<div class="cd-timeline-block">
			<div class="cd-timeline-img cd-picture">
				<span class="icn-size-24">{{ $_icons['target'] }}</span>
			</div> <!-- cd-timeline-img -->

			<div class="cd-timeline-content">
				<h2>New Goal Added</h2>
				<p>Brian Jacobs added the goal <strong>Break 90</strong> to your development plan.</p>
				<a href="#0" class="cd-read-more">Read more</a>
				<span class="cd-date">Jan 14</span>
			</div> <!-- cd-timeline-content -->
		</div> <!-- cd-timeline-block -->

		<div class="cd-timeline-block">
			<div class="cd-timeline-img cd-movie">
				<span class="icn-size-24">{{ $_icons['stats'] }}</span>
			</div> <!-- cd-timeline-img -->

			<div class="cd-timeline-content">
				<h2>New Stat Added</h2>
				<p>Congratulations on shooting 89 at Mill Creek Golf Club! Keep up the great work!</p>
				<a href="#0" class="cd-read-more">Read more</a>
				<span class="cd-date">Jan 18</span>
			</div> <!-- cd-timeline-content -->
		</div> <!-- cd-timeline-block -->

		<div class="cd-timeline-block">
			<div class="cd-timeline-img cd-picture">
				<span class="icn-size-24">{{ $_icons['target'] }}</span>
			</div> <!-- cd-timeline-img -->

			<div class="cd-timeline-content">
				<h2>Title of section 3</h2>
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Excepturi, obcaecati, quisquam id molestias eaque asperiores voluptatibus cupiditate error assumenda delectus odit similique earum voluptatem doloremque dolorem ipsam quae rerum quis. Odit, itaque, deserunt corporis vero ipsum nisi eius odio natus ullam provident pariatur temporibus quia eos repellat consequuntur perferendis enim amet quae quasi repudiandae sed quod veniam dolore possimus rem voluptatum eveniet eligendi quis fugiat aliquam sunt similique aut adipisci.</p>
				<a href="#0" class="cd-read-more">Read more</a>
				<span class="cd-date">Jan 24</span>
			</div> <!-- cd-timeline-content -->
		</div> <!-- cd-timeline-block -->

		<div class="cd-timeline-block">
			<div class="cd-timeline-img cd-location">
				<span class="icn-size-24">{{ $_icons['comments'] }}</span>
			</div> <!-- cd-timeline-img -->

			<div class="cd-timeline-content">
				<h2>Title of section 4</h2>
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iusto, optio, dolorum provident rerum aut hic quasi placeat iure tempora laudantium ipsa ad debitis unde? Iste voluptatibus minus veritatis qui ut.</p>
				<a href="#0" class="cd-read-more">Read more</a>
				<span class="cd-date">Feb 14</span>
			</div> <!-- cd-timeline-content -->
		</div> <!-- cd-timeline-block -->

		<div class="cd-timeline-block">
			<div class="cd-timeline-img cd-location">
				<span class="icn-size-24">{{ $_icons['comments'] }}</span>
			</div> <!-- cd-timeline-img -->

			<div class="cd-timeline-content">
				<h2>Title of section 5</h2>
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iusto, optio, dolorum provident rerum.</p>
				<a href="#0" class="cd-read-more">Read more</a>
				<span class="cd-date">Feb 18</span>
			</div> <!-- cd-timeline-content -->
		</div> <!-- cd-timeline-block -->

		<div class="cd-timeline-block">
			<div class="cd-timeline-img cd-movie">
				<span class="icn-size-24">{{ $_icons['stats'] }}</span>
			</div> <!-- cd-timeline-img -->

			<div class="cd-timeline-content">
				<h2>Final Section</h2>
				<p>This is the content of the last section</p>
				<span class="cd-date">Feb 26</span>
			</div> <!-- cd-timeline-content -->
		</div> <!-- cd-timeline-block -->
	</section> <!-- cd-timeline -->












	@if ($plan->goals->count() > 0)
		<div class="row">
		@foreach ($plan->goals as $goal)
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">
							<div class="pull-right text-muted">
								@if ($goal->conversations->count() > 0)
									{{ $_icons['comments'] }}
								@endif
								{{ $_icons['stats'] }}
							</div>
							{{ $goal->present()->title }}
						</h3>
					</div>
					<div class="panel-body">
						{{ $goal->present()->summary }}
					</div>
					<div class="panel-footer">
						<div class="visible-xs visible-sm">
							<div class="row">
								<div class="col-sm-4">
									<p><a href="#" class="btn btn-default btn-lg btn-block">View</a></p>
								</div>
								@if ($_currentUser->isStaff())
									<div class="col-sm-4">
										<p><a href="#" class="btn btn-default btn-lg btn-block">Edit</a></p>
									</div>
									<div class="col-sm-4">
										<p><a href="#" class="btn btn-danger btn-lg btn-block">Remove</a></p>
									</div>
								@endif
							</div>
						</div>
						<div class="visible-md visible-lg">
							<a href="#" class="btn btn-sm btn-default icn-size-16 pull-right">{{ $_icons['forward'] }}</a>
							@if ($_currentUser->isStaff())
								<a href="#" class="btn btn-sm btn-default icn-size-16">{{ $_icons['edit'] }}</a>
								<a href="#" class="btn btn-sm btn-danger icn-size-16">{{ $_icons['remove'] }}</a>
							@endif
						</div>
					</div>
				</div>
			</div>
		@endforeach
		</div>

		<hr>
	@endif

	{{ Form::open() }}
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">Add to the Conversation</label>
					{{ Form::textarea('content', null, ['class' => 'form-control', 'rows' => 3]) }}
				</div>
			</div>
			<div class="col-md-3">
				<label class="control-label visible-md visible-lg">&nbsp;</label>
				{{ Form::button("Submit", ['type' => 'submit', 'class' => 'btn btn-lg btn-block btn-primary']) }}
			</div>
		</div>
	{{ Form::close() }}

	{{ $plan->present()->conversation }}
@stop

@section('styles')
	{{ HTML::style('css/timeline.css') }}
@stop

@section('scripts')
	<script>
		jQuery(document).ready(function($){
	var $timeline_block = $('.cd-timeline-block');

	//hide timeline blocks which are outside the viewport
	$timeline_block.each(function(){
		if($(this).offset().top > $(window).scrollTop()+$(window).height()*0.75) {
			$(this).find('.cd-timeline-img, .cd-timeline-content').addClass('is-hidden');
		}
	});

	//on scolling, show/animate timeline blocks when enter the viewport
	$(window).on('scroll', function(){
		$timeline_block.each(function(){
			if( $(this).offset().top <= $(window).scrollTop()+$(window).height()*0.75 && $(this).find('.cd-timeline-img').hasClass('is-hidden') ) {
				$(this).find('.cd-timeline-img, .cd-timeline-content').removeClass('is-hidden').addClass('bounce-in');
			}
		});
	});
});
	</script>
@stop