<?php

namespace Craftsys\Msg91\SMS;

use Craftsys\Msg91\Client;
use Craftsys\Msg91\Service as ServicesService;

class Service extends ServicesService
{
    /**
     * Options for Request
     * @var \Craftsys\Msg91\SMS\Options
     */
    protected $options;

    /**
     * Create a new service instance
     * @param \Craftsys\Msg91\Client $client
     * @param int|string|\Craftsys\Msg91\SMS\Options|\Craftsys\Msg91\Msg91Message $payload
     * @return void
     */
    public function __construct(Client $client, $payload = null)
    {
        $this->client = $client;
        $this->options = (new Options())->resolveConfig($this->client->getConfig());
        $this->updateOptionsWithPayload($payload);
    }

    /**
     * Send sms
     * @return \Craftsys\Msg91\Requests\Request
     */
    public function send()
    {
        return $this->sendRequest(SendRequest::class);
    }

    /**
     * Set the receipients of the message
     * @param int|null $mobile - receipient's mobile numbers
     * @return $this
     */
    public function to($mobiles = null)
    {
        $this->options->to($mobiles);
        return $this;
    }

    /**
     * Set the flow id for the sms
     * @param string|null $flow_id - flow id for sms
     * @return $this
     */
    public function flow($flow_id = null)
    {
        $this->options->flow($flow_id);
        return $this;
    }

    /**
     * Set the message content (same as message method)
     * @param string|null $message
     * @return $this
     */
    public function content($message = '')
    {
        $this->options->content($message);
        return $this;
    }
}
