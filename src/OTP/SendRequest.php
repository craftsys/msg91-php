<?php

namespace Craftsys\Msg91\OTP;

use Craftsys\Msg91\Support\Request;
use Craftsys\Msg91\URLs;

class SendRequest extends Request
{
    protected $url = URLs::OTP_URL;
}
