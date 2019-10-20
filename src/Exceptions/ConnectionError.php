<?php

namespace Craftsys\Msg91\Exceptions;

use Exception;
use GuzzleHttp\Exception\ClientException;

class ConnectionError extends Exception
{

    public function __construct(ClientException $exception)
    {
        if (!$exception->hasResponse()) {
            return new Exception('Msg91 responded with an error but no response body found');
        }
        $statusCode = $exception->getResponse()->getStatusCode();
        $result = json_decode($exception->getResponse()->getBody(), false);
        $description = $result->description ?? 'no description given';
        return new Exception("Msg91 responded with an error `{$statusCode} - {$description}`");
    }
}
