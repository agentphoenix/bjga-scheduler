<?php

require '../vendor/autoload.php';

$browser = new Ikimea\Browser\Browser;

?><!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Brian Jacobs Golf Scheduler :: Error</title>
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
	</head>
	<body>
		<div class="container">
			<h1 class="text-center text-danger">Uh oh!</h1>

			<p class="lead text-center">Unfortunately, it looks like you're using a browser that we don't support. This can be easily remedied by using another browser or updating your current browser.</p>

			<p class="lead text-center">In order to use the Brian Jacobs Golf Scheduler with <?php $browser->getBrowser();?>, you should be using at least version ?X?.</p>
		</div>
	</body>
</html>