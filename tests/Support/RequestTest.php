<?php

namespace Craftsys\Tests\Msg91\Support;

use Craftsys\Msg91\Exceptions\ResponseErrorException;
use Craftsys\Msg91\Options;
use Craftsys\Msg91\Support\Request;
use Craftsys\Tests\Msg91\TestCase;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Middleware;

class RequestTest extends TestCase
{
    protected $container = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->container = [];
    }

    protected function createMockHttpClient(
        $status_code = 200,
        $body = [
            "type" => "success", "message" => "Success"
        ]
    ): HttpClient {
        $history = Middleware::history($this->container);
        $mock = new MockHandler([
            new Response($status_code, [], json_encode($body)),
        ]);

        $handler = HandlerStack::create($mock);
        $handler->push($history);

        $client = new HttpClient(['handler' => $handler]);
        return $client;
    }

    public function test_response_error_when_invalid_request()
    {
        $this->expectException(ResponseErrorException::class);
        (new SampleRequest($this->createMockHttpClient(422), (new SampleOptions())))
            ->handle();

        $this->expectException(ResponseErrorException::class);
        (new SampleRequest($this->createMockHttpClient(200, ['type' => 'error', 'message' => 'Testing the error response with 200 code']), (new SampleOptions())))->handle();

        $this->expectException(ResponseErrorException::class);
        (new SampleRequest($this->createMockHttpClient(200, ['msg_type' => 'error', 'msg' => 'Testing the error response with 200 code']), (new SampleOptions())))->handle();
    }
}

class SampleRequest extends Request
{
    protected $url = "https://apis.craftsys.com";
}

class SampleOptions extends Options
{

    protected $payload = [
        'authkey' => 'random_auth_key'
    ];
}
