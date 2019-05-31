<?php

namespace EmailGateway;

use EmailGateway\Adapter\AdapterInterface;

/**
 * Class EmailGateway
 *
 * @package EmailGateway
 */
class EmailGateway
{
	/**
	 * @var AdapterInterface
	 */
	private $adapter;

	/**
	 * EmailGateway constructor.
	 *
	 * @param       $adapter could be like: SendGridAdp::class
	 * @param array $data email sending data
	 * @param array $config adapter configuration
	 */
	public function __construct($adapter, array $data, array $config)
	{
		$this->adapter = new $adapter($data, $config);
	}

	/**
	 * Send email by selected adapter
	 *
	 * @return mixed|array
	 */
	public function send()
	{
		return $this->adapter->send();
	}
}
