<?php

/*Route::get('/', function()
{
	return View::make('pages.index');
});*/

//Route::get('/', 'HomeController@getIndex');

//require 'routes/book.php';
//require 'routes/manage.php';
//require 'routes/ajax.php';

Route::get('import', function()
{
	// Start a customers array
	$customers = array();

	if (App::environment() != 'production')
	{
		$faker = Faker\Factory::create();
	}

	// Open the file for reading
	$handle = fopen(App::make('path.base').'/CustomerList.csv', 'r');

	if ($handle)
	{
		$rowCount = 0;

		while (($data = fgetcsv($handle)) !== false)
		{
			if ($rowCount > 0)
			{
				// Clean up the names
				$name = str_replace('    ', ' ', $data[0]);
				$name = str_replace('   ', ' ', $name);
				$name = str_replace('  ', ' ', $name);
				$name = trim($name);
				$name = ucwords($name);

				// Don't use real email addresses in DEV
				$email = (App::environment() == 'production') ? $data[3] : $faker->safeEmail;

				// Clean up the phone numbers
				$phone = preg_replace('~.*(\d{3})[^\d]*(\d{3})[^\d]*(\d{4}).*~', '$1-$2-$3', $data[2]);
				
				// Set the address
				$address = $data[1];

				$customers[] = array(
					'name'		=> $name,
					'email'		=> $email,
					'phone'		=> $phone,
					'address'	=> $address,
				);
			}

			++$rowCount;
		}
	}

	// Close the file
	fclose($handle);

	sd($customers);
});

Route::get('calendar', function()
{
	$calendar = new Sabre\VObject\Component\VCalendar();

	$calendar->add('VEVENT', array(
		'SUMMARY' => 'Event 1',
		'DTSTART' => new DateTime('2013-12-18 10:00:00', new DateTimeZone('America/New_York')),
		'DTEND' => new DateTime('2013-12-18 11:00:00', new DateTimeZone('America/New_York')),
	));
	$calendar->add('VEVENT', array(
		'SUMMARY' => 'Event 2',
		'DTSTART' => new DateTime('2013-12-18 11:00:00', new DateTimeZone('America/New_York')),
		'DTEND' => new DateTime('2013-12-18 12:00:00', new DateTimeZone('America/New_York')),
	));
	$calendar->add('VEVENT', array(
		'SUMMARY' => 'Event 3',
		'DTSTART' => new DateTime('2013-12-18 13:00:00', new DateTimeZone('America/New_York')),
		'DTEND' => new DateTime('2013-12-18 15:00:00', new DateTimeZone('America/New_York')),
	));
	$calendar->add('VEVENT', array(
		'SUMMARY' => 'Event 4',
		'DTSTART' => new DateTime('2013-12-18 15:00:00', new DateTimeZone('America/New_York')),
		'DTEND' => new DateTime('2013-12-18 16:00:00', new DateTimeZone('America/New_York')),
	));

	File::put(App::make('path.public').'/calendars/DavidVanScott.ics', $calendar->serialize());

	/*$h = fopen(App::make('path.public').'/calendars/DavidVanScott.ics', 'a+');

	$calendar = Sabre\VObject\Reader::read($h);
	$calendar->add('VEVENT', array(
		'SUMMARY' => 'Event 1',
		'DTSTART' => new DateTime('2013-12-18 13:00:00', new DateTimeZone('America/New_York')),
		'DTEND' => new DateTime('2013-12-18 15:00:00', new DateTimeZone('America/New_York')),
	));
	$calendar->add('VEVENT', array(
		'SUMMARY' => 'Event 2',
		'DTSTART' => new DateTime('2013-12-18 15:00:00', new DateTimeZone('America/New_York')),
		'DTEND' => new DateTime('2013-12-18 16:00:00', new DateTimeZone('America/New_York')),
	));

	fwrite($h, $calendar->serialize());
	fclose($h);

	//File::put(App::make('path.public').'/calendars/DavidVanScott.ics', $calendar->serialize());

	/*$handle = fopen(App::make('path.public').'/calendars/DavidVanScott.ics', 'r');

	$c = Sabre\VObject\Reader::read($handle);

	s($c->children);*/

	/*$splitter = new Sabre\VObject\Splitter\ICalendar($handle);

	while($event = $splitter->getNext())
	{
		s((string)$event->SUMMARY);
	}*/

	return 'done';
});

Route::get('schedule', function()
{
	$staff = StaffModel::find(2);

	$schedule = $staff->schedule()->getModel()->newInstance()
		->fill(array('day' => 8, 'availability' => ''));

	$staff->schedule()->save($schedule);

	s($staff->schedule->toArray());
});