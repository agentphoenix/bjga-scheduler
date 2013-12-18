<?php

class SchedulerCustomerSeeder extends Seeder {

	public function run()
	{
		// Start a customers array
		$customers = array();

		if (App::environment() != 'production')
		{
			$faker = Faker\Factory::create();
		}

		// Open the file for reading
		$handle = fopen(App::make('path.base').'/CustomerList.csv', 'r');

		// Make sure we have a file to use
		if ($handle)
		{
			$rowCount = 0;

			while (($data = fgetcsv($handle)) !== false)
			{
				// The first row should be ignored...
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

					// Clean up the phone number
					$phone = preg_replace('~.*(\d{3})[^\d]*(\d{3})[^\d]*(\d{4}).*~', '$1-$2-$3', $data[2]);
					
					// Set the address
					$address = $data[1];

					$customers[] = array(
						'name'		=> $name,
						'email'		=> $email,
						'phone'		=> $phone,
						'address'	=> $address,
						'password'	=> Hash::make('password'),
					);
				}

				++$rowCount;
			}
		}

		// Close the file
		fclose($handle);

		foreach ($customers as $customer)
		{
			User::create($customer);
		}
	}

}