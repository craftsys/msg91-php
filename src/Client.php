<?php

namespace Craftsys\MSG91;

use GuzzleHttp\Client as HttpClient;

class Client
{
    /**
     * HTTP Client
     * @var HttpClient
     */
    protected $http;


    /**
     * Authentication token
     * @var string|null
     */
    protected $token;


    public function __construct($token = null, HttpClient $httpClient = null)
    {
        $this->token = $token;
        $this->http = $httpClient;
    }

    /**
     * Get the authentication token
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * Set the authentication token
     */
    public function setToken(?string $token = null): self
    {
        $this->token = $token;
        return $this;
    }

    public function otp($otp = null): OTPMessage
    {
        return $this->service(new OTPMessage($otp));
    }

    protected function service($service)
    {
        return $service
            ->token($this->token)
            ->httpClient($this->http);
    }
}
