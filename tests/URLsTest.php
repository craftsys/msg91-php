<?php

namespace Craftsys\Tests\Msg91;

use Craftsys\Msg91\URLs;
use Craftsys\Tests\Msg91\TestCase;
use GuzzleHttp\Client;

class URLsTest extends TestCase
{
    protected function check_not_404(string $url, string $method = "post")
    {
        $client = new Client();
        $method = strtolower($method);
        /** @var \GuzzleHttp\Psr7\Response  $response */
        $response = $client->{$method}($url);
        $this->assertNotEquals(404, $response->getStatusCode());
    }

    public function test_send_otp_url()
    {
        $this->check_not_404(URLs::OTP_URL);
    }

    public function test_verify_otp_url()
    {
        $this->check_not_404(URLs::OTP_VERIFY_URL);
    }

    public function test_resend_otp_url()
    {
        $this->check_not_404(URLs::OTP_RESEND_URL);
    }

    public function test_send_sms()
    {
        $this->check_not_404(URLs::SEND_SMS_URL);
    }
}
