<?php

namespace Craftsys\Tests\Msg91;

use Craftsys\Msg91\Client;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Middleware;

class OtpServiceTest extends TestCase
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
            new Response(200, [], json_encode(["type" => "success", "message" => "OTP Send successfully"])),
        ]);

        $handler = HandlerStack::create($mock);
        $handler->push($history);

        $client = new HttpClient(['handler' => $handler]);
        return $client;
    }

    public function test_otp_send()
    {
        $phone_number = 919999999999;
        $payload = (new Client($this->config, $this->createMockHttpClient()))
            ->otp()
            ->to($phone_number)
            ->send();
        $this->assertNotNull($payload);
        // make sure there was exacly on request
        $this->assertCount(1, $this->container);
        // check the request
        $transaction = $this->container[0];
        // check the method
        $this->assertEquals("POST", $transaction['request']->getMethod());
        // check the request data
        $data = [];
        parse_str($transaction['request']->getBody()->getContents(), $data);
        $this->assertArrayHasKey('mobile', $data);
        $this->assertEquals($phone_number, $data['mobile']);
        $this->assertArrayHasKey('authkey', $data);
        $this->assertEquals($this->config['key'], $data['authkey']);
    }

    public function test_verify_otp()
    {
        $phone_number = 919999999999;
        $otp = 1234;
        $payload = (new Client($this->config, $this->createMockHttpClient()))
            ->otp($otp)
            ->to($phone_number)
            ->verify();
        $this->assertNotNull($payload);
        // make sure there was exacly on request
        $this->assertCount(1, $this->container);
        // check the request
        $transaction = $this->container[0];
        // check the method
        $this->assertEquals("POST", $transaction['request']->getMethod());
        // check the request data
        $data = [];
        parse_str($transaction['request']->getBody()->getContents(), $data);
        $this->assertArrayHasKey('mobile', $data);
        $this->assertEquals($phone_number, $data['mobile']);
        $this->assertArrayHasKey('authkey', $data);
        $this->assertEquals($this->config['key'], $data['authkey']);
        $this->assertArrayHasKey('otp', $data);
        $this->assertEquals($otp, $data['otp']);
    }
}
