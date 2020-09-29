<?php

namespace Craftsys\Msg91\OTP;

use Craftsys\Msg91\Client;
use Craftsys\Msg91\Support\Service;

class OTPService extends Service
{
    /**
     * Options for Request
     * @var \Craftsys\Msg91\OTP\Options
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
     * Set the receipients of the message
     * @param int|null $mobile - receipient's mobile number
     * @return $this
     */
    public function to($mobile = null)
    {
        $this->options->to($mobile);
        return $this;
    }

    /**
     * Set the method of resending OTP to "text"
     * @return $this
     */
    public function viaText()
    {
        return $this->method("text");
    }

    /**
     * Set the method of resending OTP to "voice"
     * @return $this
     */
    public function viaVoice()
    {
        return $this->method("voice");
    }

    /**
     * Set the method when resending the otp
     * @param string|null $method
     * @return $this
     */
    protected function method($method = '')
    {
        $this->options->method($method);
        return $this;
    }

    /**
     * Set the template id for OTPs
     * @return $this
     */
    public function template($template_id = null)
    {
        $this->options->template($template_id);
        return $this;
    }

    /**
     * Send otp
     * @return \Craftsys\Msg91\Response
     */
    public function send()
    {
        return $this->sendRequest(SendRequest::class);
    }

    public function verify()
    {
        return $this->sendRequest(VerifyRequest::class);
    }

    public function resend()
    {
        return $this->sendRequest(ResendRequest::class);
    }
}
