<?php namespace Scheduler\Services;

use UserModel;
use Mailchimp\Mailchimp as MailService;

class MailChimpService {

	protected $apiKey;
	protected $service;

	public function __construct(MailService $mailService)
	{
		$this->service = $mailService;
	}

	public function findUser(UserModel $user)
	{
		return $this->service->lists->memberInfo($listId, array($user->email));
	}

}