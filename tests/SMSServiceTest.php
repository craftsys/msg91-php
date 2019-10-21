<?php

namespace Craftsys\Tests\Msg91;

use Craftsys\Msg91\Client;
use Craftsys\Msg91\Exceptions\ValidationException;
use Craftsys\Msg91\Response as CraftsysResponse;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Middleware;

class SMSServiceTest extends TestCase
{
    protected $config =  [
        "key" => "12345678901234567890"
    ];

    protected $container = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->container = [];
    }

    protected function createMockHttpClient(): HttpClient
    {
        $history = Middleware::history($this->container);
        $mock = new MockHandler([
            new Response(200, [], json_encode(["type" => "success", "message" => "SMS Send successfully"])),
        ]);

        $handler = HandlerStack::create($mock);
        $handler->push($history);

        $client = new HttpClient(['handler' => $handler]);
        return $client;
    }

    public function test_sms_send()
    {
        $phone_number = 919999999999;
        $message = "My message";
        $response = (new Client($this->config, $this->createMockHttpClient()))
            ->sms()
            ->message($message)
            ->to($phone_number)
            ->send();

        $this->assertInstanceOf(CraftsysResponse::class, $response);
        // make sure there was exacly on request
        $this->assertCount(1, $this->container);
        // check the request
        $transaction = $this->container[0];
        // check the method
        $this->assertEquals("POST", $transaction['request']->getMethod());
        // check the request data
        $data = [];
        parse_str($transaction['request']->getBody()->getContents(), $data);
        $this->assertArrayHasKey('mobiles', $data);
        $this->assertEquals($phone_number, $data['mobiles']);
        $this->assertArrayHasKey('authkey', $data);
        $this->assertEquals($this->config['key'], $data['authkey']);
        $this->assertArrayHasKey('message', $data);
        $this->assertEquals($message, $data['message']);
    }
}
