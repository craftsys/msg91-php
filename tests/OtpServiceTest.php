<?php

namespace Craftsys\Tests\Msg91;

use Craftsys\Msg91\Client;
use Craftsys\Msg91\Exceptions\ResponseErrorException;
use Craftsys\Msg91\Exceptions\ValidationException;
use Craftsys\Msg91\Options;
use Craftsys\Msg91\Response as CraftsysResponse;
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
        $phone_number = 919999999999;
        $response = (new Client($this->config, $this->createMockHttpClient()))
            ->otp()
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
        $this->assertArrayHasKey('mobile', $data);
        $this->assertEquals($phone_number, $data['mobile']);
        $this->assertArrayHasKey('authkey', $data);
        $this->assertEquals($this->config['key'], $data['authkey']);
    }


    public function test_verify_otp()
    {
        $phone_number = 919999999999;
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
        $data = [];
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
        $phone_number = 919999999999;
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
        $data = [];
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
        $phone_number = 919999999999;
        $this->expectException(ValidationException::class);
        (new Client([], $this->createMockHttpClient()))
            ->otp()
            ->to($phone_number)
            ->send();
    }

    public function test_response_error_when_invalid_request()
    {
        $phone_number = 919999999999;
        $this->expectException(ResponseErrorException::class);
        (new Client($this->config, $this->createMockHttpClient(422)))
            ->otp()
            ->to($phone_number)
            ->send();

        $this->expectException(ResponseErrorException::class);
        (new Client($this->config, $this->createMockHttpClient(200, ['type' => 'error', 'body' => 'Testing the error response with 200 code'])))
            ->otp()
            ->to($phone_number)
            ->send();
    }
}
