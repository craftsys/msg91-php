<?php

namespace Craftsys\Msg91\OTP;

use Craftsys\Msg91\Requests\Request;
use Craftsys\Msg91\Requests\URLs;

class SendRequest extends Request
{
    protected $url = URLs::OTP_URL;
}
