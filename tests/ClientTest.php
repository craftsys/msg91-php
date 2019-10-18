<?php

namespace Craftsys\MSG91\Test;

use Craftsys\MSG91\Client;

class ClientTest extends TestCase
{
    protected $token = "token";

    public function test_otp_send()
    {
        $response = (new Client($this->token))
            ->otp(1212)
            ->to(919559752299)
            ->country(91)
            ->send();
        $this->assertEquals(200, $response->getStatusCode());
    }
}
