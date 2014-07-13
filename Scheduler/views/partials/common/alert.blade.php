@if (Session::has('flash.message'))
	<?php $class = "alert-".Session::get('flash.level');?>
	<?php $content = Session::get('flash.message');?>
@endif

<div class="alert{{ (isset($class)) ? ' '.$class : ' alert-warning' }} fade in">
	{{ $content }}
</div>