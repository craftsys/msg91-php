<?php

namespace Craftsys\Msg91;

use GuzzleHttp\Client as HttpClient;

class Client
{
    /**
     * HTTP Client
     * @var HttpClient
     */
    protected $http;


    /**
     * Configuration
     * @var Config
     */
    protected $config;


    public function __construct(array $config = null, HttpClient $httpClient = null)
    {
        $this->config = new Config($config);
        $this->http = $httpClient;
    }

    /**
     * Get the configuration
     */
    public function getConfig(): Config
    {
        return $this->config;
    }

    /**
     * Set the new configuration
     */
    public function setConfig(array $config = null): self
    {
        $this->config = new Config($config);
        return $this;
    }

    /**
     * The OTP service which provide functionality for sending, verifying and resending OTPs
     */
    public function otp($otp = null): OTPMessage
    {
        return new OTPMessage($this->config, $otp, $this->http);
    }
}
