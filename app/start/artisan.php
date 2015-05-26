<?php

Artisan::add(new Scheduler\Commands\ThankYouMessageCommand);
Artisan::add(new Scheduler\Commands\AppointmentReminderMessageCommand);
Artisan::add(new Scheduler\Commands\UpdateCalendarCommand);
Artisan::add(new Scheduler\Commands\CreditCleanupCommand);
Artisan::add(new Scheduler\Commands\NewStaffServicesCommand);
Artisan::add(new Scheduler\Commands\DailyNotificationsCommand);
