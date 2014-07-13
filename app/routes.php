<?php

Route::get('dev', function()
{
	$user = Auth::user();
	$credits = $user->credits();
	sd($credits);
});