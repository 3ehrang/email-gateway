<?php
/**
 * Adapter Interface
 */

namespace EmailGateway\Adapter;

/**
 * Each Adapter must implement this interface in order to connect with a email platform.
 */
interface AdapterInterface
{

	/**
	 * AdapterInterface constructor.
	 *
	 * @param array $data     array of sending data
	 *                        [
	 *						    'subject'       => 'email subject',
	 *                          'from'          => 'sender@example.com',
	 *                          'fromName'      => 'Sender Name'
	 *						    'to'            => 'to-person@example.com',
	 *						    'toName'        => 'Person Name',
	 *						    'contentType'   => 'text/html' OR 'text/string',
	 *   						'content'       => 'email body content'
	 *					      ]
	 * @param array $config
	 */
    public function __construct(array $data, array $config);

    public function send();
}
