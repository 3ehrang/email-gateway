<?php
/**
 * Postmark email adapter.
 */

namespace EmailGateway\Adapter;

use EmailGateway\Adapter\AdapterInterface;
use Postmark\Models\PostmarkException;
use Postmark\PostmarkClient;

/**
 * Class PostmarkAdp
 *
 * @package EmailGateway\Adapter
 */
class PostmarkAdp implements AdapterInterface
{
    /**
     * Email sending client
     *
     * @var PostmarkClient
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
     * @param $data array
     * @param $config array('serverToken' => 'Your-Server-Token')
     *
     */
    public function __construct(array $data, array $config)
    {
        $this->client = new PostmarkClient($config['serverToken']);
        $this->data = $data;
        $this->email = [];
    }

    /**
     * Prepare email's sending data
     */
    private function prepareData()
    {
        if ($this->data['contentType'] == 'text/html') {

            $this->email['HtmlBody'] = $this->data['content'];
            $this->email['TextBody'] = null;

        } elseif ($this->data['contentType'] == 'text/string') {

            $this->email['TextBody'] = $this->data['content'];
            $this->email['HtmlBody'] = null;

        }

        $this->email['from']    = $this->data['from'];
        $this->email['to']      = $this->data['to'];
        $this->email['subject'] = $this->data['subject'];
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function send()
    {
        $this->prepareData();

        // Send email
        try {

            $response = $this->client->sendEmail(
                $this->email['from'],
                $this->email['to'],
                $this->email['subject'],
                $this->email['HtmlBody'],
                $this->email['TextBody']
            );

            // Success
            return [
                'status' => 'success',
                'data' => [
                    'service'   => 'Postmark',
                    'response'  => $response
                ]
            ];

        // Fail
        } catch(PostmarkException $ex) {
            // If client is able to communicate with the API in a timely fashion,
            // but the message data is invalid, or there's a server error,
            // a PostmarkException can be thrown.

            return [
                'status' => 'fail',
                'data' => [
                    'service'   => 'Postmark',
                    'httpStatusCode' => $ex->httpStatusCode,
                    'message' => $ex->message,
                    'postmarkApiErrorCode' => $ex->postmarkApiErrorCode
                ]
            ];

        // Error
        } catch(\Exception $e) {

            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];

        }
    }
}
