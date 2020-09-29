<?php

namespace Craftsys\Tests\Msg91\SMS;

use Craftsys\Msg91\Client;
use Craftsys\Msg91\Exceptions\ValidationException;
use Craftsys\Msg91\Support\Response as CraftsysResponse;
use Craftsys\Tests\Msg91\TestCase;
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
        $response = (new Client($this->config, $this->createMockHttpClient()))
            ->sms()
            ->from("SENDER_ID")
            ->flow("flow_id_here")
            ->to($phone_number)
            ->send();


        $this->assertInstanceOf(CraftsysResponse::class, $response);
        // make sure there was exacly on request
        $this->assertCount(1, $this->container);
        // check the request
        $transaction = $this->container[0];
        // check the method
        $this->assertEquals("POST", $transaction['request']->getMethod());
    }

    public function test_flow_id_is_required()
    {
        $phone_number = 919999999999;
        $this->expectException(ValidationException::class);
        (new Client($this->config, $this->createMockHttpClient()))
            ->sms()
            ->message('A message')
            ->to($phone_number)
            ->send();
    }
}
