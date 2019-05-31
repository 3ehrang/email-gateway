<?php
/**
 * SendGrid email adapter.
 */

namespace EmailGateway\Adapter;

use EmailGateway\Adapter\AdapterInterface;
use SendGrid\Mail\Mail;

class SendGridAdp implements AdapterInterface
{
    /**
     * Email sending client
     *
     * @var \SendGrid
     */
    private $client;

    /**
     * setting email sending data
     *
     * @var Mail
     */
    private $mail;

    /**
     * Email data
     *
     * @var array
     */
    private $data;

    /**
     * @param array $data
     * @param array $config ['apiKey' => 'Your-Api-Key']
     */
    public function __construct(array $data, array $config)
    {
        $this->client   = new \SendGrid($config['apiKey']);
        $this->mail     = new Mail();
        $this->data     = $data;
    }

    /**
     * Prepare email's sending data
     */
    private function prepareData()
    {
        $this->mail->setFrom($this->data['from'], $this->data['fromName']);
        $this->mail->setSubject($this->data['subject']);
        $this->mail->addTo($this->data['to'], $this->data['toName']);
        $this->mail->addContent($this->data['contentType'], $this->data['content']);
    }

    /**
     * @return array
     * @throws \SendGrid\Mail\TypeException
     */
    public function send()
    {
        $this->prepareData();

        // Send and return response
        try {

            $response = $this->client->send($this->mail);

            // Success
            if ($response->statusCode() == 202) {

                return [
                    'status' => 'success',
                    'data' => [
                        'service'   => 'SendGrid',
                        'response' => $response
                    ]
                ];

            // Fail
            } else {

                return [
                    'status' => 'fail',
                    'data' => [
                        'service'  => 'SendGrid',
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
