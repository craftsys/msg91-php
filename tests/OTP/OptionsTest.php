<?php

namespace Craftsys\Tests\Msg91\OTP;

use Craftsys\Msg91\OTP\Options;
use Craftsys\Tests\Msg91\TestCase;

class OptionsTest extends TestCase
{
    public function test_init_with_int_will_set_otp()
    {
        $options = (new Options(1234));
        $this->assertEquals(1234, $options->getPayloadForKey('otp'));
    }

    public function test_can_merge_with_another_options()
    {
        $options = (new Options())->mergeWith(new Options(1234));
        $this->assertEquals(1234, $options->getPayloadForKey('otp'));
    }
}
