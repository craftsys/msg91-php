<?php

namespace Craftsys\Msg91;

use GuzzleHttp\Client as HttpClient;

abstract class Service
{
    /**
     * The msg91 client instance
     * @var \Craftsys\Msg91\Client
     */
    protected $client;

    /**
     * Options for Request
     */
    public function getOptions(): Options
    {
        return $this->options;
    }

    /**
     * Get the http client
     * @return \GuzzleHttp\Client
     */
    protected function getHttpClient()
    {
        return $this->client->getHttpClient() ?: new HttpClient();
    }

    /**
     * Get the initial message
     * @param int|string|\Craftsys\Msg91\Msg91Message|\Craftsys\Msg91\Options $payload
     * @return void
     */
    protected function updateOptionsWithPayload($payload = null)
    {
        $this->getOptions()->mergeWith($payload);
    }

    /**
     * Set the receipient(s)
     * @param int|null $mobile
     * @return $this
     */
    public function to($mobile = null)
    {
        $this->getOptions()->to($mobile);
        return $this;
    }

    /**
     * Set the content of message
     * @param string|null $message
     * @return $this
     */
    public function message($message = '')
    {
        $this->getOptions()->message($message);
        return $this;
    }

    /**
     * Pass any other options to the message
     * @param mixed $options
     * @return $this;
     */
    public function options($options = null)
    {
        $this->getOptions()->mergeWith($options);
        return $this;
    }

    /**
     * Create a new instance of given request
     * @param string $request - Request class name
     * @return \Craftsys\Msg91\Response
     */
    protected function sendRequest(string $request)
    {
        return (new $request($this->getHttpClient(), $this->getOptions()))->handle();
    }
}
