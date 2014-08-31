<?php namespace Scheduler\Api\Controllers;

use App,
	Response,
	Controller;
use Symfony\Component\HttpKernel\Exception;

class ApiController extends Controller {

	public function errorForbidden($message = 'Forbidden')
	{
		throw new Exception\AccessDeniedHttpException($message);
	}

	public function errorInternalError($message = 'Internal Error')
	{
		throw new Exception\HttpException(500, $message);
	}

	public function errorNotFound($message = 'Resource Not Found')
	{
		throw new Exception\NotFoundHttpException($message);
	}

	public function errorUnauthorized($message = 'Unauthorized')
	{
		throw new Exception\UnauthorizedHttpException($message);
	}

	public function errorBadRequest($message = 'Bad request')
	{
		throw new Exception\BadRequestHttpException($message);
	}

}