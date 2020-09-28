<?php

namespace Craftsys\Msg91\SMS;

use Craftsys\Msg91\Requests\Request;
use Craftsys\Msg91\Requests\URLs;

class SendRequest extends Request
{
    protected $url = URLs::SEND_SMS_URL;
}
