<?php namespace Scheduler\Api\Controllers;

use Response,
	Controller;
use League\Fractal\Manager,
	League\Fractal\Resource\Item,
	League\Fractal\Resource\Collection;

class ApiController extends Controller {

	protected $fractal;
	protected $statusCode = 200;

	const CODE_WRONG_ARGS = 'GEN-FUBARGS';
	const CODE_NOT_FOUND = 'GEN-LIKETHEWIND';
	const CODE_INTERNAL_ERROR = 'GEN-AAARGH';
	const CODE_UNAUTHORIZED = 'GEN-MAYBGTFO';
	const CODE_FORBIDDEN = 'GEN-GTFO';

	public function __construct(Manager $fractal)
	{
		$this->fractal = $fractal;
	}

	public function getStatusCode()
	{
		return $this->statusCode;
	}

	public function setStatusCode($statusCode)
	{
		$this->statusCode = $statusCode;

		return $this;
	}

	protected function respondWithItem($item, $callback)
	{
		$resource = new Item($item, $callback);

		$rootScope = $this->fractal->createData($resource);

		return $this->respondWithArray($rootScope->toArray());
	}

	protected function respondWithCollection($collection, $callback)
	{
		$resource = new Collection($collection, $callback);

		$rootScope = $this->fractal->createData($resource);

		return $this->respondWithArray($rootScope->toArray());
	}

	protected function respondWithArray(array $array, array $headers = array())
	{
		return Response::json($array, $this->statusCode, $headers);
	}

	protected function respondWithError($message, $errorCode)
	{
		if ($this->statusCode === 200)
		{
			trigger_error("You better have a good reason for erroring on a 200...", E_USER_WARNING);
		}

		return $this->respondWithArray(array(
			'error' => array(
				'code' => $errorCode,
				'http_code' => $this->statusCode,
				'message' => $message,
			)
		));
	}

	public function errorForbidden($message = 'Forbidden')
	{
		return $this->setStatusCode(403)->respondWithError($message, self::CODE_FORBIDDEN);
	}

	public function errorInternalError($message = 'Internal Error')
	{
		return $this->setStatusCode(500)->respondWithError($message, self::CODE_INTERNAL_ERROR);
	}

	public function errorNotFound($message = 'Resource Not Found')
	{
		return $this->setStatusCode(404)->respondWithError($message, self::CODE_NOT_FOUND);
	}

	public function errorUnauthorized($message = 'Unauthorized')
	{
		return $this->setStatusCode(401)->respondWithError($message, self::CODE_UNAUTHORIZED);
	}

	public function errorWrongArgs($message = 'Wrong Arguments')
	{
		return $this->setStatusCode(400)->respondWithError($message, self::CODE_WRONG_ARGS);
	}

}