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