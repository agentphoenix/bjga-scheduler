<?php

class SchedulerSettingSeeder extends Seeder {

	public function run()
	{
		$settings = array(
			array(
				'key'			=> "lead_out_time",
				'value'			=> "15",
				'label'			=> "Lead Out Time",
				'description'	=> "The amount of time after an appointment before a new appointment can be booked."
			),
			array(
				'key'			=> "confirmation_email_booked",
				'value'			=> "1",
				'label'			=> "Email Confirmation on Booking",
				'description'	=> "Send an email confirmation that an appointment has been booked."
			),
			array(
				'key'			=> "reminder_email",
				'value'			=> "24",
				'label'			=> "Reminder Email Schedule",
				'description'	=> "How many hours before the appointment should a reminder email be sent?"
			),
			array(
				'key'			=> "default_location",
				'value'			=> "Mill Creek Golf Club",
				'label'			=> "Default Location",
				'description'	=> "Where do your events generally take place?"
			),
		);

		foreach ($settings as $setting)
		{
			Setting::create($setting);
		}
	}

}