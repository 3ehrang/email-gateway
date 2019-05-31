<?php
/**
 * SendPulse email adapter.
 */

namespace EmailGateway\Adapter;

use EmailGateway\Adapter\AdapterInterface;
use Sendpulse\RestApi\ApiClient;
use Sendpulse\RestApi\Storage\FileStorage;

/**
 * Class SendPulseAdp
 * @package EmailGateway\Adapter
 */
class SendPulseAdp implements AdapterInterface
{
    /**
     * Email sending client
     *
     * @var ApiClient
     */
    private $client;

    /**
     * Email sending content
     * @var
     */
    private $email;

    /**
     * Email getting data
     *
     * @var array
     */
    private $data;

    /**
     * Send Pulse config data
     *
     * @var array
     */
    private $config;

    /**
     * SendPulse constructor.
     *
     * @param array $data
     * @param array $config array(
     *                  'id' => 'Your-ID',
     *                  'secret' => 'Your-Secret'
     *                )
     *
     * @throws \Exception
     */
    public function __construct(array $data, array $config)
    {
        $this->config = $config;
        $this->data = $data;
        $this->email = [];
    }

    /**
     * Prepare email's sending data
     */
    private function prepareData()
    {
        if ($this->data['contentType'] == 'text/html') {

            $this->email['html'] = $this->data['content'];
            $this->email['text'] = '';

        } elseif ($this->data['contentType'] == 'text/string') {

            $this->email['html'] = '';
            $this->email['text'] = $this->data['content'];

        }

        $this->email['subject'] = $this->data['subject'];

        $this->email['from'] = [
            'name'  => $this->data['fromName'],
            'email' => $this->data['from']
        ];

        $this->email['to'] = [
            [
                'name'  => $this->data['toName'],
                'email' => $this->data['to']
            ]
        ];
    }

    /**
     * Send email
     *
     * @return array
     */
    public function send()
    {
    	// Make connection
        try {

            $this->client = new ApiClient($this->config['id'], $this->config['secret'], new FileStorage());


        } catch (\Exception $e) {

            return [
                'status' => 'fail',
                'data' => [
                    'service'  => 'SendPulse',
                    'response' => $e->getMessage()
                ]
            ];

        }

		// Prepare sending data
        $this->prepareData();

        // Send and return response
        try {

            $response = $this->client->smtpSendMail($this->email);

            // Success
            if (isset($response->result) &&  $response->result == true) {

                return [
                    'status' => 'success',
                    'data' => [
                        'service'  => 'SendPulse',
                        'response' => $response
                    ]
                ];

            // Fail
            } elseif (isset($response->is_error) &&  $response->is_error == true) {

                return [
                    'status' => 'fail',
                    'data' => [
                        'service'  => 'SendPulse',
                        'response' => $response
                    ]
                ];

            }

        // Error
        } catch (\Exception $e) {

            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];

        }
    }
}
