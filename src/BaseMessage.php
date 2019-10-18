<?php

namespace Craftsys\MSG91;

use Craftsys\MSG91\Exceptions\ConnectionError;
use Craftsys\MSG91\Exceptions\ResponseError;
use Craftsys\MSG91\Exceptions\TokenRequired;
use Exception;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use JsonSerializable;

class BaseMessage implements JsonSerializable
{

    /**
     * Keys for payload
     */
    const COUNTRY_KEY = "country";
    const MESSAGE_KEY = "message";
    const SENDER_KEY = "sender";
    const TOKEN_KEY = "authkey";

    /**
     * HTTP Client
     * @var HttpClient
     */
    protected $http;


    /**
     * Payload for the message e.g. text, mobile number etc
     * @var array
     */
    protected $payload = [
        self::COUNTRY_KEY => 91,
    ];

    public function __construct($token = null, HttpClient $httpClient = null)
    {
        $this->http = $httpClient;
        $this->token($token);
    }

    /**
     * Set the authentication token
     */
    public function token(?string $token = null): self
    {
        return $this->setPayloadFor(static::TOKEN_KEY, $token);
    }

    /**
     * Country to which the mobile number belongs
     */
    public function country(int $country_code): self
    {
        return $this->setPayloadFor(static::COUNTRY_KEY, $country_code);
    }

    public function from(string $sender): self
    {
        return $this->setPayloadFor(static::SENDER_KEY, $sender);
    }

    public function message(string $message): self
    {
        return $this->setPayloadFor(static::MESSAGE_KEY, $message);
    }

    /**
     * Any addition options for the payload
     */
    public function options(array $options = []): self
    {
        $this->payload = array_merge($this->payload, $options);
        return $this;
    }

    /**
     * Set the payload for a key
     */
    public function setPayloadFor(string $key, $value = null): self
    {
        $this->payload[$key] = $value;
        return $this;
    }

    /**
     * Get the payload of the message
     */
    public function getPayload(): array
    {
        return $this->payload;
    }


    /**
     * Get payload value for given key.
     */
    public function getPayloadFor(string $key)
    {
        return $this->getPayload()[$key] ?? null;
    }

    /**
     * Return the payload
     */
    public function toArray(): array
    {
        return $this->getPayload();
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    public function formatMobileNumber($mobile_no)
    {
        return (int) preg_replace('/[^\d]/mi', '', (string) $mobile_no);
    }

    /**
     * Get the http client, create new one if none exists
     */
    public function getHttpClient(): HttpClient
    {
        return $this->http ?? new HttpClient();
    }

    public function getToken()
    {
        return $this->getPayloadFor(static::TOKEN_KEY);
    }

    public function setHttpClient(HttpClient $httpClient = null): self
    {
        $this->http = $httpClient;
        return $this;
    }

    protected function sendRequest(string $endpoint): ?Response
    {
        $params = $this->toArray();
        // authentication token is required
        if (!$this->getToken()) {
            throw new TokenRequired();
        }
        try {
            return new Response(
                $this->getHttpClient()->post(
                    $endpoint,
                    [
                        'form_params' => $params,
                    ]
                )
            );
        } catch (ClientException $exception) {
            new ConnectionError($exception);
        } catch (Exception $exception) {
            throw new ResponseError($exception->getMessage());
        }
    }
}
