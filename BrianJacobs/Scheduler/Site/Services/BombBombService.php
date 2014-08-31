<?php namespace Scheduler\Services;

class BombBombService {

	protected $email;
	protected $apiKey;
	protected $password;
	protected $endpoint = 'https://app.bombbomb.com/app/api/api.php';
	protected $verify_ssl = false;

	public function __construct()
	{
		$this->email	= $_ENV['BOMBBOMB_EMAIL'];
		$this->apiKey	= $_ENV['BOMBBOMB_API_KEY'];
		$this->password	= $_ENV['BOMBBOMB_PASSWORD'];
	}

	public function addContact(array $data)
	{
		return $this->makeRequest('AddContact', $data);
	}

	public function isValidLogin()
	{
		return $this->makeRequest('IsValidLogin');
	}

	public function lists()
	{
		return $this->makeRequest('GetLists');
	}

	protected function makeRequest($method, array $args = array())
	{
		// Grab the API key
		$args['api_key'] = $this->apiKey;

		// Start building the URL
		$url = $this->endpoint.'?method='.$method;

		// Loop through the arguments and build the output
		foreach ($args as $key => $value)
		{
			$output[] = "{$key}={$value}";
		}

		// Finish building the URL
		$url.= '&'.implode('&', $output);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');       
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->verify_ssl);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($args));

		if ($_ENV['PROXY'])
		{
			curl_setopt($ch, CURLOPT_PROXYPORT, $_ENV['PROXY_PORT']);
			curl_setopt($ch, CURLOPT_PROXYTYPE, $_ENV['PROXY_TYPE']);
			curl_setopt($ch, CURLOPT_PROXY, $_ENV['PROXY_HOST']);
			curl_setopt($ch, CURLOPT_PROXYUSERPWD, $_ENV['PROXY_USERNAME'].':'.$_ENV['PROXY_PASSWORD']);
		}

		$result = curl_exec($ch);
		curl_close($ch);

		return $result ? json_decode($result, true) : false;
	}

}