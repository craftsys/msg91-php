<?php

namespace Craftsys\Msg91;

use Craftsys\Msg91\Exceptions\ResponseErrorException;
use GuzzleHttp\Psr7\Response as GuzzleHttpResponse;

class Response
{
    /**
     * Http client
     * @var \GuzzleHttp\Psr7\Response
     */
    protected $response;

    /**
     * Status of the
     * @var int
     */
    protected $status = 422;

    /**
     * Response data
     * @var array
     */
    protected $data = [];

    /**
     * Response errors
     * @var array|null
     */
    protected $errors = null;

    /**
     * Response message
     * @var string
     */
    protected $message = "";

    public function __construct(GuzzleHttpResponse $response)
    {
        $this->response = $response;
        $this->handle();
    }

    /**
     * Handle the request
     */
    protected function handle()
    {
        $response = $this->response;
        $status_code = $response->getStatusCode();
        $body = (array) json_decode($response->getBody()->getContents());
        if ($body) {
            $this->data = $body;
            if (isset($body['type'])) {
                $type = $body['type'];
                if ($type === "error") {
                    $status_code = 422;
                }
            }
            $this->message = $body["message"] ?? "No response message";
        }
        $this->status_code = $status_code;
        if ((int) $status_code / 100 !== 2) {
            throw new ResponseErrorException($this->message, $status_code, null, $this->data);
        }
    }

    /**
     * Get the response status code
     * @var int
     */
    public function getStatusCode()
    {
        return $this->status_code;
    }

    /**
     * Get the response data
     * @var array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Get the response message
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}
