<?php

Route::get('dev', function()
{
	$user = Auth::user();
	sd($user->credits());
});