<?php

namespace Craftsys\MSG91\Test;

use Craftsys\MSG91\Client;

class ClientTest extends TestCase
{
    protected $token = "<token>";

    public function test_otp_send()
    {
        $response = (new Client($this->token))
            ->otp()
            ->to(919999999999)
            ->send();
        $this->assertEquals(200, $response->getStatusCode());
    }
}
