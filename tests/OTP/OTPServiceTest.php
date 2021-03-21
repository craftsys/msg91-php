<?php

namespace Craftsys\Tests\Msg91\OTP;

use Craftsys\Msg91\Client;
use Craftsys\Msg91\Exceptions\ValidationException;
use Craftsys\Msg91\Support\Response as CraftsysResponse;
use Craftsys\Tests\Msg91\TestCase;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Middleware;

class OTPServiceTest extends TestCase
{
    protected $config =  [
        "key" => "12345678901234567890"
    ];

    protected $phone_number = 919999999999;

    protected $container = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->container = [];
    }

    protected function createMockHttpClient(
        $status_code = 200,
        $body = [
            "type" => "success", "message" => "OTP Send successfully"
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

    public function test_otp_send()
    {
        $phone_number = $this->phone_number;
        $response = (new Client($this->config, $this->createMockHttpClient()))
            ->otp()
            ->from("SMSIND")
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
        $data = (array) json_decode($transaction['request']->getBody()->getContents());
        $this->assertArrayHasKey('mobile', $data);
        $this->assertEquals($phone_number, $data['mobile']);
        $this->assertArrayHasKey('authkey', $data);
        $this->assertEquals($this->config['key'], $data['authkey']);
    }


    public function test_verify_otp()
    {
        $phone_number = $this->phone_number;
        $otp = 1234;
        $response = (new Client($this->config, $this->createMockHttpClient()))
            ->otp($otp)
            ->to($phone_number)
            ->verify();
        $this->assertInstanceOf(CraftsysResponse::class, $response);
        // make sure there was exacly on request
        $this->assertCount(1, $this->container);
        // check the request
        $transaction = $this->container[0];
        // check the method
        $this->assertEquals("POST", $transaction['request']->getMethod());
        // check the request data
        parse_str($transaction['request']->getBody()->getContents(), $data);
        $this->assertArrayHasKey('mobile', $data);
        $this->assertEquals($phone_number, $data['mobile']);
        $this->assertArrayHasKey('authkey', $data);
        $this->assertEquals($this->config['key'], $data['authkey']);
        $this->assertArrayHasKey('otp', $data);
        $this->assertEquals($otp, $data['otp']);
    }

    public function test_otp_resend()
    {
        $phone_number = $this->phone_number;
        $response = (new Client($this->config, $this->createMockHttpClient()))
            ->otp()
            ->to($phone_number)
            ->viaVoice()
            ->resend();
        $this->assertInstanceOf(CraftsysResponse::class, $response);
        // make sure there was exacly on request
        $this->assertCount(1, $this->container);
        // check the request
        $transaction = $this->container[0];
        // check the method
        $this->assertEquals("POST", $transaction['request']->getMethod());
        // check the request data
        parse_str($transaction['request']->getBody()->getContents(), $data);
        $this->assertArrayHasKey('mobile', $data);
        $this->assertEquals($phone_number, $data['mobile']);
        $this->assertArrayHasKey('authkey', $data);
        $this->assertEquals($this->config['key'], $data['authkey']);
        $this->assertArrayHasKey('retrytype', $data);
        $this->assertEquals("voice", $data['retrytype']);
    }

    public function test_api_key_required()
    {
        $phone_number = $this->phone_number;
        $this->expectException(ValidationException::class);
        (new Client([], $this->createMockHttpClient()))
            ->otp()
            ->to($phone_number)
            ->send();
    }
}
