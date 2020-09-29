<?php

namespace Craftsys\Msg91\SMS;

use Craftsys\Msg91\Client;
use Craftsys\Msg91\Support\Service;

class SMSService extends Service
{
    /**
     * Options for Request
     * @var \Craftsys\Msg91\SMS\Options
     */
    protected $options;

    /**
     * Create a new service instance
     * @param \Craftsys\Msg91\Client $client
     * @param int|string|\Craftsys\Msg91\Contracts\Options $payload
     * @return void
     */
    public function __construct(Client $client, $payload = null)
    {
        $this->client = $client;
        $this->options = (new Options())
            ->resolveConfig($this->client->getConfig())
            ->mergeWith($payload);
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
     * Set the recipients of the message
     * @param int|null $mobile - recipients's mobile numbers
     * @return $this
     */
    public function to($mobiles = null)
    {
        $this->options->to($mobiles);
        return $this;
    }

    /**
     * Set the recipients with placeholders
     * @param array|null $recipients - recipients with mobile number and placeholders
     * @return $this
     */
    public function recipients($recipients = null)
    {
        $this->options->recipients($recipients);
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

    /**
     * Set a variable's value for all the recipients
     * @param string $name - name of the variable in the template
     * @param string|number|null $value - value for the variable to be placed in template
     * @return $this
     */
    public function variable(string $name, $value = null): self
    {
        $this->options->variable($name, $value);
        return $this;
    }
}
