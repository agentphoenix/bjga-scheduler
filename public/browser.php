<?php

require '../vendor/autoload.php';

$b = new Ikimea\Browser\Browser;

$browser = $b->getBrowser();
$currentVersion = $b->getVersion();

switch ($browser)
{
	case 'Firefox':
		$minVersion = 19.0;
		$updateLink = 'http://getfirefox.com';
	break;

	case 'Chrome':
		$minVersion = 18.0;
		$updateLink = 'http://google.com/chrome';
	break;

	case 'Internet Explorer':
		$minVersion = 9.0;
		$updateLink = 'http://windows.microsoft.com/en-us/internet-explorer/download-ie-MCM';
	break;
}

?><!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Brian Jacobs Golf Scheduler :: Error</title>

		<link href="http://fonts.googleapis.com/css?family=Exo+2:500" rel="stylesheet">
		<link href="http://fonts.googleapis.com/css?family=Open+Sans:400" rel="stylesheet">
		<link href="http://fonts.googleapis.com/css?family=Crete+Round" rel="stylesheet">
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
		<style>
			body {
				color: #444;
				font-family: "Open Sans", helvetica, arial, sans-serif;
				font-size: 16px;
				line-height: 1.75;
			}

			h1 {
				font-family: "Crete Round", times, georgia, serif;
				color: #af1515;
			}

			.btn.btn-primary {
				margin: 1.5em 0;

				font-family: "Exo 2";
				font-weight: 500;
				background: transparent;
				border-width: 2px;
				color: #44932b;
				border-color: #44932b;

				-moz-border-radius: 50px;
				-webkit-border-radius: 50px;
				border-radius: 50px;
			}
			.btn.btn-primary:hover, .btn.btn-primary:focus,
			.btn.btn-primary:active {
				background: #44932b;
				color: #fff;
				border-color: #44932b;
			}

			.container {
				width: 700px;
			}
		</style>
	</head>
	<body>
		<div class="container">
			<div class="page-header">
				<h1>Uh oh!</h1>
			</div>

			<p>Unfortunately, it looks like you're using a browser that we don't support. This can be easily remedied by using another browser or updating your current browser.</p>

			<p>In order to use the Brian Jacobs Golf Scheduler with <?php echo $browser;?> you need to be using at least version <?php echo $minVersion;?>, but you're only using version <?php echo $currentVersion;?>.</p>

			<a href="<?php echo $updateLink;?>" class="btn btn-lg btn-block btn-primary">Update Your Browser Now</a>
		</div>
	</body>
</html>