<?php namespace Scheduler\Events;

use DB;
use ServiceFullException;

class Appointment {

	public function beforeCreate($model)
	{
		// Get the service
		$service = $model->service;

		// If we have a user limit and we're at the limit, stop execution
		if ($service->user_limit > 0 and $model->attendees->count() == $service->user_limit)
		{
			throw new ServiceFullException;
		}

		/**
		 * Determine credits
		 */
		/*
		// Get the user's credits
		$credits = $model->user->credit;
		
		if ($credits !== null)
		{
			if ($credits->type == 'hours')
			{
				if ((int) $credits->amount < (int) $service->duration)
				{
					// If the user doesn't have enough credits to cover
					// the entire duration of the service, they'll have
					// to pay with money instead
					$model->payment_type = 'money';
					$model->paid = (int) false;
				}
				else
				{
					// Update the appointment
					$model->payment_type = 'credits';
					$model->paid = (int) true;
					
					// Set the remaining amount
					$remainingCredits = (int) $credits->amount - (int) $service->duration;
					
					if ($remainingCredits > 0)
					{
						// Update how many credits the user has left
						$credits->amount = (int) $credits->amount - (int) $service->duration;
						$credits->save();
					}
					else
					{
						// Remove the credits account because they have none
						$credits->delete();
					}
				}
			}
			
			if ($credits->type == 'money')
			{
				// Mark this appointment as using a GC
				$model->gift_certificate = (int) true;
				
				if ((int) $credits->amount >= (int) $service->price)
				{
					// The user has more credit than the service costs
					$model->gift_certificate_amount = $service->price;
					$model->payment_type = 'credits';
					$model->paid = (int) true;
					$model->amount = 0;
					
					// Update how much money is left on the account
					$credits->amount = (int) $credits->amount - (int) $service->price;
					$credits->save();
				}
				
				if ((int) $credits->amount < (int) $service->price)
				{
					// The service costs more than the credit the user has
					$model->gift_certificate_amount = (int) $service->price - (int) $model->gift_certificate_amount;
					$model->payment_type = 'money';
					$model->paid = (int) false;
					$model->amount = (int) $service->price - (int) $credits->amount;
					
					// Remove the credits record because they have none left
					$credits->delete();
				}
			}
		}
		*/
	}
	
	public function afterCreate($model)
	{
		//
	}
	
	public function beforeDelete($model)
	{
		//
	}
	
}