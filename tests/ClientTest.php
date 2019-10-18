<?php

namespace Craftsys\MSG91\Test;

use Craftsys\MSG91\Client;

class ClientTest extends TestCase
{
    public function test_works()
    {
        $hello = (new Client())->otp();
        $this->assertEquals("WIP", $hello);
    }
}
