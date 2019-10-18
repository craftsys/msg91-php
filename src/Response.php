<?php

namespace Craftsys\MSG91;

use Psr\Http\Message\ResponseInterface;
use Exception;

class Response
{
    /**
     * Response
     * @var ResponseInterface
     */
    protected $response;

    /**
     * Body of the response
     * @var array | null
     */
    protected $body;

    /**
     * Status code of the response
     * @var int
     */
    protected $status_code;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
        $this->handle();
    }

    protected function handle()
    {
        $response = $this->response;
        $this->status_code = $response->getStatusCode();
        $body = (array) json_decode($response->getBody()->getContents());
        $this->body = $body;
        if ($body["type"] === "error") {
            // if we have an error, change the status code
            if ($this->status_code === 200) {
                $this->status_code = 422;
            }
            throw new Exception($body['message']);
        }
    }

    public function getStatusCode(): int
    {
        return $this->status_code;
    }

    public function getBody(): ?array
    {
        return $this->body;
    }

    public function hasErrors()
    {
        return $this->status_code !== 200;
    }
}
