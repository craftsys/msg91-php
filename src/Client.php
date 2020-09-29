<?php

namespace Craftsys\Msg91;

use Craftsys\Msg91\OTP\OTPService;
use Craftsys\Msg91\SMS\SMSService;
use GuzzleHttp\Client as GuzzleHttpClient;

/**
 * The Msg91 Client. This is responsible for all the interactions with
 * the msg91 apis.
 */
class Client
{
    /**
     * Client's configuration
     * @var \Craftsys\Msg91\Config
     */
    protected $config;

    /**
     * Http Client for sending requests
     * @var \GuzzleHttp\Client;
     */
    protected $httpClient;

    /**
     * Construct a new Msg91 Client instance
     *
     * @param array|null $config
     * @param \GuzzleHttp\Client $httpClient
     * @return void
     */
    public function __construct($config = null, GuzzleHttpClient $httpClient = null)
    {
        $this->httpClient = $httpClient;
        $this->config = new Config($config);
    }

    /**
     * Return the configuration
     * @return \Craftsys\Msg91\Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Set the configuration
     * @param array|null $config
     * @return $this
     */
    public function setConfig($config = null)
    {
        $this->config = new Config($config);
        return $this;
    }

    /**
     * Set the http client
     */
    public function setHttpClient(GuzzleHttpClient $httpClient): self
    {
        $this->httpClient = $httpClient;
        return $this;
    }

    /**
     * Get the http client
     */
    public function getHttpClient(): GuzzleHttpClient
    {
        return $this->httpClient ?: new GuzzleHttpClient();
    }

    /**
     * Access to OPT services
     *
     * @param mixed $payload - initial payload for request
     */
    public function otp($payload = null): OTPService
    {
        return new OTPService($this, $payload);
    }

    /**
     * Access to SMS services
     *
     * @param mixed $payload - initial payload for request
     */
    public function sms($payload = null): SMSService
    {
        return new SMSService($this, $payload);
    }
}
